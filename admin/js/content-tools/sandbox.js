(function() {
  var CloudinaryImageUploader, ImageUploader, _resizeTimeout;

  ImageUploader = (function() {
    ImageUploader.imagePath = 'image.png';

    ImageUploader.imageSize = [600, 174];

    function ImageUploader(dialog) {
      this._dialog = dialog;
      this._dialog.addEventListener('cancel', (function(_this) {
        return function() {
          return _this._onCancel();
        };
      })(this));
      this._dialog.addEventListener('imageuploader.cancelupload', (function(_this) {
        return function() {
          return _this._onCancelUpload();
        };
      })(this));
      this._dialog.addEventListener('imageuploader.clear', (function(_this) {
        return function() {
          return _this._onClear();
        };
      })(this));
      this._dialog.addEventListener('imageuploader.fileready', (function(_this) {
        return function(ev) {
          return _this._onFileReady(ev.detail().file);
        };
      })(this));
      this._dialog.addEventListener('imageuploader.mount', (function(_this) {
        return function() {
          return _this._onMount();
        };
      })(this));
      this._dialog.addEventListener('imageuploader.rotateccw', (function(_this) {
        return function() {
          return _this._onRotateCCW();
        };
      })(this));
      this._dialog.addEventListener('imageuploader.rotatecw', (function(_this) {
        return function() {
          return _this._onRotateCW();
        };
      })(this));
      this._dialog.addEventListener('imageuploader.save', (function(_this) {
        return function() {
          return _this._onSave();
        };
      })(this));
      this._dialog.addEventListener('imageuploader.unmount', (function(_this) {
        return function() {
          return _this._onUnmount();
        };
      })(this));
    }

    ImageUploader.prototype._onCancel = function() {};

    ImageUploader.prototype._onCancelUpload = function() {
      clearTimeout(this._uploadingTimeout);
      return this._dialog.state('empty');
    };

    ImageUploader.prototype._onClear = function() {
      return this._dialog.clear();
    };

    ImageUploader.prototype._onFileReady = function(file) {
      var upload;
      console.log(file);
      this._dialog.progress(0);
      this._dialog.state('uploading');
      upload = (function(_this) {
        return function() {
          var progress;
          progress = _this._dialog.progress();
          progress += 1;
          if (progress <= 100) {
            _this._dialog.progress(progress);
            return _this._uploadingTimeout = setTimeout(upload, 25);
          } else {
            return _this._dialog.populate(ImageUploader.imagePath, ImageUploader.imageSize);
          }
        };
      })(this);
      return this._uploadingTimeout = setTimeout(upload, 25);
    };

    ImageUploader.prototype._onMount = function() {};

    ImageUploader.prototype._onRotateCCW = function() {
      var clearBusy;
      this._dialog.busy(true);
      clearBusy = (function(_this) {
        return function() {
          return _this._dialog.busy(false);
        };
      })(this);
      return setTimeout(clearBusy, 1500);
    };

    ImageUploader.prototype._onRotateCW = function() {
      var clearBusy;
      this._dialog.busy(true);
      clearBusy = (function(_this) {
        return function() {
          return _this._dialog.busy(false);
        };
      })(this);
      return setTimeout(clearBusy, 1500);
    };

    ImageUploader.prototype._onSave = function() {
      var clearBusy;
      this._dialog.busy(true);
      clearBusy = (function(_this) {
        return function() {
          _this._dialog.busy(false);
          return _this._dialog.save(ImageUploader.imagePath, ImageUploader.imageSize, {
            alt: 'Example of bad variable names'
          });
        };
      })(this);
      return setTimeout(clearBusy, 1500);
    };

    ImageUploader.prototype._onUnmount = function() {};

    ImageUploader.createImageUploader = function(dialog) {
      return new ImageUploader(dialog);
    };

    return ImageUploader;

  })();

  window.ImageUploader = ImageUploader;

  CloudinaryImageUploader = (function() {
    CloudinaryImageUploader.CLOUD_NAME = '';

    CloudinaryImageUploader.DRAFT_DIMENSIONS = [600, 600];

    CloudinaryImageUploader.INSERT_DIMENSIONS = [400, 400];

    CloudinaryImageUploader.RETRIEVE_URL = 'http://res.cloudinary.com/#CLOUD_NAME#/image/upload';

    CloudinaryImageUploader.UPLOAD_PRESET = '';

    CloudinaryImageUploader.UPLOAD_URL = 'https://api.cloudinary.com/v1_1/#CLOUD_NAME#/image/upload';

    function CloudinaryImageUploader(dialog) {
      this._dialog = dialog;
      this._dialog.addEventListener('imageuploader.cancelupload', (function(_this) {
        return function() {
          return _this._onCancelUpload();
        };
      })(this));
      this._dialog.addEventListener('imageuploader.clear', (function(_this) {
        return function() {
          return _this._onClear();
        };
      })(this));
      this._dialog.addEventListener('imageuploader.fileready', (function(_this) {
        return function(files) {
          return _this._onFileReady(files);
        };
      })(this));
      this._dialog.addEventListener('imageuploader.rotateccw', (function(_this) {
        return function() {
          return _this._onRotate(-90);
        };
      })(this));
      this._dialog.addEventListener('imageuploader.rotatecw', (function(_this) {
        return function() {
          return _this._onRotate(90);
        };
      })(this));
      this._dialog.addEventListener('imageuploader.save', (function(_this) {
        return function() {
          return _this._onSave();
        };
      })(this));
    }

    CloudinaryImageUploader.prototype._onCancelUpload = function() {
      if (this._xhr) {
        this._xhr.upload.removeEventListener('progress', this._xhrProgress);
        this._xhr.removeEventListener('readystatechange', this._xhrComplete);
        this._xhr.abort();
      }
      return this._dialog.state('empty');
    };

    CloudinaryImageUploader.prototype._onClear = function() {
      this._dialog.clear();
      return this._image = null;
    };

    CloudinaryImageUploader.prototype._onFileReady = function(ev) {
      var file, formData;
      file = ev.detail().file;
      this._dialog.progress(0);
      this._dialog.state('uploading');
      formData = new FormData();
      formData.append('file', file);
      formData.append('upload_preset', this.constructor.UPLOAD_PRESET);
      this._xhr = new XMLHttpRequest();
      this._xhr.open('POST', this.constructor._getUploadURL(), true);
      this._xhrProgress = (function(_this) {
        return function(ev) {
          return _this._dialog.progress((ev.loaded / ev.total) * 100);
        };
      })(this);
      this._xhrComplete = (function(_this) {
        return function(ev) {
          var filename, readyState, status, text;
          readyState = ev.target.readyState;
          text = ev.target.responseText;
          status = ev.target.status;
          if (readyState !== 4) {
            return;
          }
          _this._xhr = null;
          if (parseInt(status) === 200) {
            _this._image = JSON.parse(text);
            _this._image.angle = 0;
            _this._image.width = parseInt(_this._image.width);
            _this._image.height = parseInt(_this._image.height);
            _this._image.maxWidth = _this._image.width;
            filename = _this.constructor.parseURL(_this._image.url)[0];
            _this._image.url = _this.constructor.buildURL(filename, [_this.constructor._getDraftTransform()]);
            return _this._dialog.populate(_this._image.url, [_this._image.width, _this._image.height]);
          } else {
            return new ContentTools.FlashUI('no');
          }
        };
      })(this);
      this._xhr.upload.addEventListener('progress', this._xhrProgress);
      this._xhr.addEventListener('readystatechange', this._xhrComplete);
      return this._xhr.send(formData);
    };

    CloudinaryImageUploader.prototype._onRotate = function(angle) {
      var filename, h, transforms, w;
      this._image.angle += angle;
      if (this._image.angle < 0) {
        this._image.angle += 360;
      } else if (this._image.angle > 270) {
        this._image.angle -= 360;
      }
      w = this._image.width;
      h = this._image.height;
      this._image.width = h;
      this._image.height = w;
      this._image.maxWidth = this._image.width;
      transforms = [this.constructor._getDraftTransform()];
      if (this._image.angle > 0) {
        transforms.unshift({
          a: this._image.angle
        });
      }
      filename = this.constructor.parseURL(this._image.url)[0];
      this._image.url = this.constructor.buildURL(filename, transforms);
      return this._dialog.populate(this._image.url, [this._image.width, this._image.height]);
    };

    CloudinaryImageUploader.prototype._onSave = function() {
      var attrs, cropRegion, cropTransform, filename, heightScale, ratio, transforms, widthScale;
      transforms = [];
      if (this._image.angle !== 0) {
        transforms.push({
          a: this._image.angle
        });
      }
      cropRegion = this._dialog.cropRegion();
      if (!(cropRegion.toString() === [0, 0, 1, 1].toString())) {
        cropTransform = {
          c: 'crop',
          x: parseInt(this._image.width * cropRegion[1]),
          y: parseInt(this._image.height * cropRegion[0]),
          w: parseInt(this._image.width * (cropRegion[3] - cropRegion[1])),
          h: parseInt(this._image.height * (cropRegion[2] - cropRegion[0]))
        };
        this._image.width = cropTransform.w;
        this._image.height = cropTransform.h;
        this._image.maxWidth = this._image.width;
        transforms.push(cropTransform);
      }
      if (this._image.width > this.constructor.INSERT_DIMENSIONS[0] || this._image.height > this.constructor.INSERT_DIMENSIONS[1]) {
        transforms.push({
          c: 'fit',
          w: this.constructor.INSERT_DIMENSIONS[0],
          h: this.constructor.INSERT_DIMENSIONS[1]
        });
        widthScale = this.constructor.INSERT_DIMENSIONS[0] / this._image.width;
        heightScale = this.constructor.INSERT_DIMENSIONS[1] / this._image.height;
        ratio = Math.min(widthScale, heightScale);
        this._image.width = ratio * this._image.width;
        this._image.height = ratio * this._image.height;
      }
      filename = this.constructor.parseURL(this._image.url)[0];
      this._image.url = this.constructor.buildURL(filename, transforms);
      attrs = {
        'alt': '',
        'data-ce-max-width': this._image.maxWidth
      };
      return this._dialog.save(this._image.url, [this._image.width, this._image.height], attrs);
    };

    CloudinaryImageUploader.createImageUploader = function(dialog) {
      return new this(dialog);
    };

    CloudinaryImageUploader._getDraftTransform = function() {
      return {
        w: this.DRAFT_DIMENSIONS[0],
        h: this.DRAFT_DIMENSIONS[1],
        c: 'fit'
      };
    };

    CloudinaryImageUploader._getRetrieveURL = function() {
      return this.RETRIEVE_URL.replace('#CLOUD_NAME#', this.CLOUD_NAME);
    };

    CloudinaryImageUploader._getUploadURL = function() {
      return this.UPLOAD_URL.replace('#CLOUD_NAME#', this.CLOUD_NAME);
    };

    CloudinaryImageUploader.buildURL = function(filename, transforms) {
      var name, paramStrs, parts, transform, transformStrs, value, _i, _len;
      transformStrs = [];
      for (_i = 0, _len = transforms.length; _i < _len; _i++) {
        transform = transforms[_i];
        paramStrs = [];
        for (name in transform) {
          value = transform[name];
          paramStrs.push("" + name + "_" + value);
        }
        transformStrs.push(paramStrs.join(','));
      }
      parts = [this._getRetrieveURL()];
      if (transformStrs.length > 0) {
        parts.push(transformStrs.join('/'));
      }
      parts.push(filename);
      return parts.join('/');
    };

    CloudinaryImageUploader.parseURL = function(url) {
      var filename, name, pair, part, parts, transform, transforms, value, _i, _j, _len, _len1, _ref, _ref1;
      url = url.replace(new RegExp('^' + this._getRetrieveURL()), '');
      parts = url.split('/');
      parts.shift();
      filename = parts.pop();
      if (parts.length && parts[parts.length - 1].match(/v\d+/)) {
        parts.pop();
      }
      transforms = [];
      for (_i = 0, _len = parts.length; _i < _len; _i++) {
        part = parts[_i];
        transform = {};
        _ref = part.split(',');
        for (_j = 0, _len1 = _ref.length; _j < _len1; _j++) {
          pair = _ref[_j];
          _ref1 = pair.split('_'), name = _ref1[0], value = _ref1[1];
          transform[name] = value;
        }
        transforms.push(transform);
      }
      return [filename, transforms];
    };

    return CloudinaryImageUploader;

  })();

  window.CloudinaryImageUploader = CloudinaryImageUploader;

  _resizeTimeout = null;

  ContentEdit.Root.get().bind('taint', function(element) {
    var resizeURL;
    if (element.type() !== 'Image') {
      return;
    }
    if (_resizeTimeout) {
      clearTimeout(_resizeTimeout);
    }
    resizeURL = function() {
      var cls, filename, newSize, transforms, _ref;
      cls = CloudinaryImageUploader;
      _ref = cls.parseURL(element.attr('src')), filename = _ref[0], transforms = _ref[1];
      if (filename === void 0) {
        return;
      }
      newSize = element.size();
      if (transforms.length > 0 && transforms[transforms.length - 1]['c'] === 'fill') {
        transforms.pop();
      }
      transforms.push({
        w: newSize[0],
        h: newSize[1],
        c: 'fill'
      });
      return element.attr('src', cls.buildURL(filename, transforms));
    };
    return _resizeTimeout = setTimeout(resizeURL, 500);
  });

  /*window.onload = function() {
    var FIXTURE_TOOLS, editor, req;
    ContentTools.IMAGE_UPLOADER = ImageUploader.createImageUploader;
    CloudinaryImageUploader.CLOUD_NAME = 'peixe-laranja';
    CloudinaryImageUploader.UPLOAD_PRESET = 'result_sustentavel';
    ContentTools.IMAGE_UPLOADER = function(dialog) {
      return CloudinaryImageUploader.createImageUploader(dialog);
    };
    ContentTools.StylePalette.add([new ContentTools.Style('By-line', 'article__by-line', ['p']), new ContentTools.Style('Caption', 'article__caption', ['p']), new ContentTools.Style('Example', 'example', ['pre']), new ContentTools.Style('Example + Good', 'example--good', ['pre']), new ContentTools.Style('Example + Bad', 'example--bad', ['pre'])]);
    editor = ContentTools.EditorApp.get();
    editor.init('[data-editable], [data-fixture]', 'data-name');
    editor.addEventListener('saved', function(ev) {
      var saved;
      console.log(ev.detail().regions);
      if (Object.keys(ev.detail().regions).length === 0) {
        return;
      }
      editor.busy(true);
      saved = (function(_this) {
        return function() {
          editor.busy(false);
          return new ContentTools.FlashUI('ok');
        };
      })(this);
      return setTimeout(saved, 2000);
    });
    FIXTURE_TOOLS = [['undo', 'redo', 'remove']];
    ContentEdit.Root.get().bind('focus', function(element) {
      var tools;
      if (element.isFixed()) {
        tools = FIXTURE_TOOLS;
      } else {
        tools = ContentTools.DEFAULT_TOOLS;
      }
      if (editor.toolbox().tools() !== tools) {
        return editor.toolbox().tools(tools);
      }
    });
    req = new XMLHttpRequest();
    req.overrideMimeType('application/json');
    req.open('GET', 'https://raw.githubusercontent.com/GetmeUK/ContentTools/master/translations/lp.json', true);
    return req.onreadystatechange = function(ev) {
      var translations;
      if (ev.target.readyState === 4) {
        translations = JSON.parse(ev.target.responseText);
        ContentEdit.addTranslations('lp', translations);
        return ContentEdit.LANGUAGE = 'lp';
      }
    };
  };*/

}).call(this);
