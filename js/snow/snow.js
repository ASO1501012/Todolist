/* Information
----------------------------------------------
File Name : snow.js
URL : http://www.atokala.com/
Copyright : (C)atokala
Author : Masahiro Abe
--------------------------------------------*/
var ATSnow = function(vars) {
	//コンストラクタ
	var _self = this;
	var _snows = [];
	var _scrollHeight = 0;
	var _windowWidth = 0;
	var _windowHeight = 0;
	var _doc = document;

	//デフォルトオプション
	var options = {
		classname : 'snow',
		count : 100,
		interval : 40,
		maxSize : 5,
		minSize : 1,
		leftMargin : 50,
		rightMargin : 50,
		bottomMargin : 30,
		maxDistance : 10,
		minDistance : 1
	};

	//オプションの上書き設定
	this.config = function(property) {
		for (var i in property) {
			//設定されていない時は上書きしない
			if (!vars.hasOwnProperty(i)) {
				continue;
			}
			options[i] = property[i];
		}
	}

	//イベント追加
	this.addEvent = function(eventTarget, eventName, func) {
		// モダンブラウザ
		if(eventTarget.addEventListener) {
			eventTarget.addEventListener(eventName, func, false);
		}
		// IE
		else if(window.attachEvent) {
			eventTarget.attachEvent('on'+eventName, function(){func.apply(eventTarget);});
		}
	}

	//ブラウザチェック
	var browser = {
		ua : navigator.userAgent,
		//IE
		ie : function() {
			return this.ua.indexOf('MSIE') >= 0;
		},
		//IE6
		ie6 : function() {
			return this.ua.indexOf('MSIE 6') >= 0;
		},
		//標準モード
		ieStandard : function() {
			if (_doc.compatMode && _doc.compatMode == 'CSS1Compat') {
				return true;
			}
			return false;
		},
		//iPhone
		iphone : function() {
			return this.ua.indexOf('iPhone') >= 0;
		},
		//iPad
		ipad : function() {
			return this.ua.indexOf('iPad') >= 0;
		},
		//Android
		android : function() {
			return this.ua.indexOf('Android') >= 0;
		}
	};

	//スクロール幅取得
	this.getScrollWidth = function() {
		//モダンブラウザ
		if (_doc.documentElement.scrollWidth) {
			return _doc.documentElement.scrollWidth;
		}
		else if (browser.ie() && !browser.ieStandard()) {
			return _doc.body.scrollWidth;
		}
	}

	//スクロール高さ取得
	this.getScrollHeight = function() {
		//モダンブラウザ
		if (_doc.documentElement.scrollHeight) {
			return _doc.documentElement.scrollHeight;
		}
		else if (browser.ie() && !browser.ieStandard()) {
			return _doc.body.scrollHeight;
		}
	}

	//ランダム取得
	this.getRandomInt = function(min, max) {
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}

	//ウインドウ幅取得
	this.getWindowWidth = function() {
		//モダン
		if (window.innerWidth) {
			return window.innerWidth;
		}
		//IE
		else if (browser.ie()) {
			//IE6 && 標準モード
			if (browser.ie6() && browser.ieStandard()) {
				return _doc.documentElement.clientWidth;
			}
			//IE6互換モード && 他IE
			else {
				//IE6以下
				if (!_doc.documentElement.clientWidth) {
					return _doc.body.clientWidth;
				}
				//IE6以上
				else {
					return _doc.documentElement.clientWidth;
				}
			}
		}
	}

	//ウインドウ高さ取得
	this.getWindowHeight = function() {
		//モダン
		if (window.innerHeight) {
			return window.innerHeight;
		}
		//IE
		else if (browser.ie()) {
			//IE6 && 標準モード
			if (browser.ie6() && browser.ieStandard()) {
				return _doc.documentElement.clientHeight;
			}
			//IE6互換モード && 他IE
			else {
				//IE6以下
				if (!_doc.documentElement.clientHeight) {
					return _doc.body.clientHeight;
				}
				//IE6以上
				else {
					return _doc.documentElement.clientHeight;
				}
			}
		}
	}

	this.createSnow = function() {
		var body = _doc.getElementsByTagName('body')[0];

		for(var i = 0; i < options.count; i++){
			//雪作成
			var snow = _doc.createElement('div');
			snow.className = options.classname;
			var diameter = _self.getRandomInt(options.minSize, options.maxSize);
			snow.style.width = diameter + 'px';
			snow.style.height = diameter + 'px';

			_snows[i] = {};
			var snows = _snows[i];
			snows.ele = snow;
			snows.distance = _self.getRandomInt(options.minDistance, options.maxDistance);
			snows.degree = _self.getRandomInt(1, 10);
			snows.move = 0;
			snows.size = diameter;

			body.appendChild(snow);
		}
	}

	this.snowsPositionReset = function() {
		for(var i = 0; i < options.count; i++){
			var topPosition = Math.floor(Math.random() * _scrollHeight);
			_self.snowPositionReset(_snows[i], topPosition - _scrollHeight);
		}
	}

	this.snowPositionReset = function(snow, y) {
		var leftPosition = _self.getRandomInt(options.leftMargin, _windowWidth - options.rightMargin);
		snow.ele.style.top = y + 'px';
		snow.ele.style.left = leftPosition + 'px';
		snow.x = leftPosition;
	}

	this.move = function() {
		var fall = function() {
			for(var i = 0; i < options.count; i++){
				var snow = _snows[i];
				var top = parseInt(snow.ele.style.top);
				snow.move += snow.degree;

				if (top + snow.size + snow.size >= _scrollHeight - options.bottomMargin) {
					_self.snowPositionReset(snow, 0);
				}
				else {
					snow.ele.style.top = top + snow.size + 'px';
					snow.ele.style.left = snow.x + Math.cos(snow.move * Math.PI / 180) * snow.distance + 'px';
				}
			}
			setTimeout(function(){fall()}, options.interval);
		}
		fall();
	}

	this.load = function() {
		if (browser.iphone() || browser.ipad() || browser.android()) {
			return;
		}

		//コンストラクタ
		_self.config(vars);

		_self.addEvent(window, 'load', function() {
			_scrollHeight = _self.getScrollHeight();
			_windowWidth = _self.getWindowWidth();
			_windowHeight = _self.getWindowHeight();
			_self.createSnow();
			_self.snowsPositionReset();
			_self.move();
		});

		_self.addEvent(window, 'resize', function() {
			_scrollHeight = _self.getScrollHeight();
			_windowWidth = _self.getWindowWidth();
			_windowHeight = _self.getWindowHeight();
			_self.snowsPositionReset();
		});
	}
};
