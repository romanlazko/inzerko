'use strict';

var Typeahead = require('suggestions');
var debounce = require('lodash.debounce');
var extend = require('xtend');
var EventEmitter = require('events').EventEmitter;
var localization = require('./localization');

/**
 * A address component with sane defaults for usage with enflow/laravel-address
 * @return {Address} `this`
 */

function Address(selector, options) {
  this.selector = selector;
  this.options = extend({}, this.options, options);
  this._eventEmitter = new EventEmitter();
  this.inputString = '';
  this.fresh = true;
  this.lastSelected = null;
  this.add();
}

Address.prototype = {
  selector: null,
  options: {
    minLength: 2,
    limit: 5,
    endpoint: location.origin + '/address/suggest',
    clearAndBlurOnEsc: true,
    language: null,
    getItemValue: function(item) {
      return item.label
    },
    render: function(item) {
      var splittedLabel = item.label.split(',');
      return '<div class="address-js--suggestion"><div class="address-js--suggestion-title">' + splittedLabel[0]+ '</div><div class="address-js--suggestion-address">' + splittedLabel.splice(1, splittedLabel.length).join(',') + '</div></div>';
    }
  },

  add: function() {
    var wrapper = this.selector;

    if (typeof wrapper === 'string' || wrapper instanceof String) {
      const elements = document.querySelectorAll(wrapper);
      if (elements.length === 0) {
        throw new Error("Element " + this.selector + " not found.")
      }

      if (elements.length > 1) {
        throw new Error("Address field can only be added to a single html element")
      }

      wrapper = elements[0];
    }

    this.setLanguage();

    this._onChange = this._onChange.bind(this);
    this._onKeyDown = this._onKeyDown.bind(this);
    this._onPaste = this._onPaste.bind(this);
    this._onBlur = this._onBlur.bind(this);
    this._showButton = this._showButton.bind(this);
    this._hideButton = this._hideButton.bind(this);
    this.clear = this.clear.bind(this);
    this._clear = this._clear.bind(this);
    this._clearOnBlur = this._clearOnBlur.bind(this);

    wrapper.classList.add('address-js');
    this.container = wrapper;

    this._inputEl = wrapper.querySelector('[role="address-label"]');
    this._inputEl.type = 'search';
    this._inputEl.autocomplete = 'off';
    this._inputEl.classList.add('address-js--input');

    this.setPlaceholder();

    this._inputEl.addEventListener('blur', this._onBlur);
    this._inputEl.addEventListener('keydown', debounce(this._onKeyDown, 200));
    this._inputEl.addEventListener('paste', this._onPaste);
    this._inputEl.addEventListener('change', this._onChange);
    this.container.addEventListener('mouseenter', this._showButton);
    this.container.addEventListener('mouseleave', this._hideButton);

    this._valueEl = wrapper.querySelector('[role="address-value"]');

    var actions = document.createElement('div');
    actions.classList.add('address-js--pin-right');

    this._clearEl = document.createElement('button');
    this._clearEl.setAttribute('aria-label', 'Clear');
    this._clearEl.addEventListener('click', this.clear);
    this._clearEl.className = 'address-js--button';

    var buttonIcon = this.createIcon('close', '<path d="M3.8 2.5c-.6 0-1.3.7-1.3 1.3 0 .3.2.7.5.8L7.2 9 3 13.2c-.3.3-.5.7-.5 1 0 .6.7 1.3 1.3 1.3.3 0 .7-.2 1-.5L9 10.8l4.2 4.2c.2.3.7.3 1 .3.6 0 1.3-.7 1.3-1.3 0-.3-.2-.7-.3-1l-4.4-4L15 4.6c.3-.2.5-.5.5-.8 0-.7-.7-1.3-1.3-1.3-.3 0-.7.2-1 .3L9 7.1 4.8 2.8c-.3-.1-.7-.3-1-.3z"/>')
    this._clearEl.appendChild(buttonIcon);

    this._loadingEl = this.createIcon('loading', '<path fill="#333" d="M4.4 4.4l.8.8c2.1-2.1 5.5-2.1 7.6 0l.8-.8c-2.5-2.5-6.7-2.5-9.2 0z"/><path opacity=".1" d="M12.8 12.9c-2.1 2.1-5.5 2.1-7.6 0-2.1-2.1-2.1-5.5 0-7.7l-.8-.8c-2.5 2.5-2.5 6.7 0 9.2s6.6 2.5 9.2 0 2.5-6.6 0-9.2l-.8.8c2.2 2.1 2.2 5.6 0 7.7z"/>');

    actions.appendChild(this._clearEl);
    actions.appendChild(this._loadingEl);

    wrapper.appendChild(this._valueEl);
    wrapper.appendChild(actions);

    this._typeahead = new Typeahead(this._inputEl, [], {
      filter: false,
      minLength: this.options.minLength,
      limit: this.options.limit
    });

    if (this._inputEl.value && this._valueEl.value ) {
      this._typeahead.selected = JSON.parse(this._valueEl.value);
    }

    this.setRenderFunction(this.options.render);
    this._typeahead.getItemValue = this.options.getItemValue;
  },

  createIcon: function(name, path) {
    var icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    icon.setAttribute('class', 'address-js--icon address-js--icon-' + name);
    icon.setAttribute('viewBox', '0 0 18 18');
    icon.setAttribute('xml:space','preserve');
    icon.setAttribute('width', 18);
    icon.setAttribute('height', 18);
    // IE does not have innerHTML for SVG nodes
    if (!('innerHTML' in icon)) {
      var SVGNodeContainer = document.createElement('div');
      SVGNodeContainer.innerHTML = '<svg>' + path.valueOf().toString() + '</svg>';
      var SVGNode = SVGNodeContainer.firstChild,
        SVGPath = SVGNode.firstChild;
      icon.appendChild(SVGPath);
    } else {
      icon.innerHTML = path;
    }
    return icon;
  },

  onRemove: function() {
    this.container.parentNode.removeChild(this.container);

    return this;
  },

  _onPaste: function(e){
    var value = (e.clipboardData || window.clipboardData).getData('text');
    if (value.length >= this.options.minLength) {
      this._suggest(value);
    }
  },

  _onKeyDown: function(e) {
    var ESC_KEY_CODE = 27,
      TAB_KEY_CODE = 9;

    if (e.keyCode === ESC_KEY_CODE && this.options.clearAndBlurOnEsc) {
      this._clear(e);
      return this._inputEl.blur();
    }

    // if target has shadowRoot, then get the actual active element inside the shadowRoot
    var target = e.target && e.target.shadowRoot
      ? e.target.shadowRoot.activeElement
      : e.target;
    var value = target ? target.value : '';

    if (!value) {
      this.fresh = true;
      // the user has removed all the text
      if (e.keyCode !== TAB_KEY_CODE) this.clear(e);
      return (this._clearEl.style.display = 'none');
    }

    // TAB, ESC, LEFT, RIGHT, ENTER, UP, DOWN
    if ((e.metaKey || [TAB_KEY_CODE, ESC_KEY_CODE, 37, 39, 13, 38, 40].indexOf(e.keyCode) !== -1))
      return;

    if (target.value.length >= this.options.minLength) {
      this._suggest(target.value);
    }
  },

  _showButton: function() {
    if (this._typeahead.selected) this._clearEl.style.display = 'block';
  },

  _hideButton: function() {
    if (this._typeahead.selected) this._clearEl.style.display = 'none';
  },

  _onBlur: function(e) {
    this._clearOnBlur(e);
  },

  _onChange: function() {
    var selected = this._typeahead.selected;
    if (selected  && JSON.stringify(selected) !== this.lastSelected) {
      this.lastSelected = JSON.stringify(selected);

      this._clearEl.style.display = 'none';

      this._inputEl.value = selected.label;
      this._inputEl.dispatchEvent(new Event('change'));

      this._valueEl.value = JSON.stringify(selected);
      this._valueEl.dispatchEvent(new Event('change'));

      this._eventEmitter.emit('result', { result: selected });
    }
  },

  _suggest: function(searchInput) {
    this._loadingEl.style.display = 'block';
    this._clearEl.style.display = 'none';
    this._eventEmitter.emit('loading', { query: searchInput });
    this.inputString = searchInput;
    // Possible config properties to pass to client
    var keys = [
      'limit',
      'language',
      'filter',
      'resultType',
    ];
    var self = this;
    // Create config object
    var config = keys.reduce(function(config, key) {
      if (self.options[key]) {
        // countries and language need to be passed in as arrays to client
        ['language'].indexOf(key) > -1
          ? (config[key] = self.options[key].split(/[\s,]+/))
          : (config[key] = self.options[key]);
      }
      return config;
    }, {});

    config = extend(config, { query: searchInput });

    var url = new URL(this.options.endpoint);
    Object.keys(config).forEach(key => url.searchParams.append(key, config[key]))

    var request = fetch(url);

    request
      .then(response => response.json())
      .then(
        function (response) {
          this._loadingEl.style.display = 'none';

          var res = {};
          res.items = response.data;

          res.config = config;
          if (this.fresh) {
            this.fresh = false;
          }

          if (res.items.length) {
            this._clearEl.style.display = 'block';
            this._eventEmitter.emit('results', res);
            this._typeahead.update(res.items);
          } else {
            this._clearEl.style.display = 'none';
            this._typeahead.selected = null;
            this._renderNoResults();
            this._eventEmitter.emit('results', res);
          }

        }.bind(this)
      );

    request.catch(
      function(err) {
        this._loadingEl.style.display = 'none';
        this._clearEl.style.display = 'none';
        this._typeahead.selected = null;
        this._renderError();

        this._eventEmitter.emit('results', { items: [] });
        this._eventEmitter.emit('error', { error: err });
      }.bind(this)
    );

    return request;
  },

  /**
   * Shared logic for clearing input
   * @param {Event} [ev] the event that triggered the clear, if available
   * @private
   *
   */
  _clear: function(ev) {
    if (ev) ev.preventDefault();
    this._inputEl.value = '';
    this._valueEl.value = '';
    this._typeahead.selected = null;
    this._typeahead.clear();
    this._onChange();
    this._clearEl.style.display = 'none';
    this.lastSelected = null;
    this._eventEmitter.emit('clear');
    this.fresh = true;
  },

  /**
   * Clear and then focus the input.
   * @param {Event} [ev] the event that triggered the clear, if available
   *
   */
  clear: function(ev) {
    this._clear(ev);
    this._inputEl.focus();
  },


  /**
   * Clear the input, without refocusing it. Used to implement clearOnBlur
   * constructor option.
   * @param {Event} [ev] the blur event
   * @private
   */
  _clearOnBlur: function(ev) {
    var ctx = this;

    /*
     * If relatedTarget is not found, assume user targeted the suggestions list.
     * In that case, do not clear on blur. There are other edge cases where
     * ev.relatedTarget could be null. Clicking on list always results in null
     * relatedtarget because of upstream behavior in `suggestions`.
     *
     * The ideal solution would be to check if ev.relatedTarget is a child of
     * the list. See issue #258 for details on why we can't do that yet.
     */
    if (ev.relatedTarget) {
      ctx._clear(ev);
    }
  },

  _renderError: function(){
    var errorMessage = "<div class='address-js--error'>There was an error reaching the server</div>"
    this._renderMessage(errorMessage);
  },

  _renderNoResults: function(){
    var label = localization.noResults[this.getLanguage()] || 'No results found';
    var errorMessage = "<div class='address-js--error address-js--no-results'>"+label+"</div>";
    this._renderMessage(errorMessage);
  },

  _renderMessage: function(msg){
    this._typeahead.update([]);
    this._typeahead.selected = null;
    this._typeahead.clear();
    this._typeahead.renderError(msg);
  },

  /**
   * Get the text to use as the search bar placeholder
   *
   * If placeholder is provided in options, then use options.placeholder
   * Otherwise, if language is provided in options, then use the localized string of the first language if available
   * Otherwise use the default
   *
   * @returns {String} the value to use as the search bar placeholder
   * @private
   */
  _getPlaceholderText: function(){
    if (this.options.placeholder) return this.options.placeholder;
    if (this._inputEl.placeholder) return this._inputEl.placeholder;
    return localization.placeholder[this.getLanguage()] || 'Search';
  },

  /**
   * Set the render function used in the results dropdown
   * @param {Function} fn The function to use as a render function. This function accepts address object as input and returns a string.
   * @returns {Address} this
   */
  setRenderFunction: function(fn){
    if (fn && typeof(fn) == "function"){
      this._typeahead.render = fn;
    }
    return this;
  },

  /**
   * Get the function used to render the results dropdown
   *
   * @returns {Function} the render function
   */
  getRenderFunction: function(){
    return this._typeahead.render;
  },

  /**
   * Get the language to use in UI elements and when making search requests
   *
   * Look first at the explicitly set options otherwise use the browser's language settings
   * @param {String} language Specify the language to use for response text and query result weighting. You may specify a language in the ISO 639-1 language code format.
   * @returns {Address} this
   */
  setLanguage: function(language){
    this.options.language = language || this.options.language || document.documentElement.lang || 'en';
    return this;
  },

  /**
   * Get the language to use in UI elements and when making search requests
   * @returns {String} The language(s) used by the plugin, if any
   */
  getLanguage: function(){
    return this.options.language;
  },

  /**
   * Get the value of the placeholder string
   * @returns {String} The input element's placeholder value
   */
  getPlaceholder: function(){
    return this.options.placeholder;
  },

  /**
   * Set the value of the input element's placeholder
   * @param {String} placeholder the text to use as the input element's placeholder
   * @returns {Address} this
   */
  setPlaceholder: function(placeholder){
    this.placeholder = (placeholder) ? placeholder : this._getPlaceholderText();
    this._inputEl.placeholder = this.placeholder;
    this._inputEl.setAttribute('aria-label', this.placeholder);
    return this
  },

  /**
   * Get the minimum number of characters typed to trigger results used in the plugin
   * @returns {Number} The minimum length in characters before a search is triggered
   */
  getMinLength: function(){
    return this.options.minLength;
  },

  /**
   * Set the minimum number of characters typed to trigger results used by the plugin
   * @param {Number} minLength the minimum length in characters
   * @returns {Address} this
   */
  setMinLength: function(minLength){
    this.options.minLength = minLength;
    if (this._typeahead)  this._typeahead.minLength = minLength;
    return this;
  },

  /**
   * Get the limit value for the number of results to display used by the plugin
   * @returns {Number} The limit value for the number of results to display used by the plugin
   */
  getLimit: function(){
    return this.options.limit;
  },

  /**
   * Set the limit value for the number of results to display used by the plugin
   * @param {Number} limit the number of search results to return
   * @returns {Address}
   */
  setLimit: function(limit){
    this.options.limit = limit;
    if (this._typeahead) this._typeahead.options.limit = limit;
    return this;
  },

  /**
   * Subscribe to events that happen within the plugin.
   * @param {String} type name of event. Available events and the data passed into their respective event objects are:
   *
   * - __clear__ `Emitted when the input is cleared`
   * - __loading__ `{ query } Emitted when the geocoder is looking up a query`
   * - __results__ `{ results } Fired when the geocoder returns a response`
   * - __result__ `{ result } Fired when input is set`
   * - __error__ `{ error } Error as string`
   * @param {Function} fn function that's called when the event is emitted.
   * @returns {Address} this;
   */
  on: function(type, fn) {
    this._eventEmitter.on(type, fn);
    return this;
  },

  /**
   * Remove an event
   * @returns {Address} this
   * @param {String} type Event name.
   * @param {Function} fn Function that should unsubscribe to the event emitted.
   */
  off: function(type, fn) {
    this._eventEmitter.removeEventListener(type, fn);
    return this;
  }
};

module.exports = Address;
