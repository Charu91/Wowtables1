/*
Name: 			Theme Admin Extension
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version: 	1.2.0
*/

window.theme = {};

// Panels
(function( $ ) {

	$(function() {
		$('.panel')
			.on( 'click', '.panel-actions a.fa-caret-up', function( e ) {
				e.preventDefault();

				var $this,
					$panel;

				$this = $( this );
				$panel = $this.closest( '.panel' );

				$this
					.removeClass( 'fa-caret-up' )
					.addClass( 'fa-caret-down' );

				$panel.find('.panel-body, .panel-footer').slideDown( 200 );
			})
			.on( 'click', '.panel-actions a.fa-caret-down', function( e ) {
				e.preventDefault();

				var $this,
					$panel;

				$this = $( this );
				$panel = $this.closest( '.panel' );

				$this
					.removeClass( 'fa-caret-down' )
					.addClass( 'fa-caret-up' );

				$panel.find('.panel-body, .panel-footer').slideUp( 200 );
			})
			.on( 'click', '.panel-actions a.fa-times', function( e ) {
				e.preventDefault();

				var $panel,
					$row;

				$panel = $(this).closest('.panel');

				if ( !!( $panel.parent('div').attr('class') || '' ).match( /col-(xs|sm|md|lg)/g ) && $panel.siblings().length === 0 ) {
					$row = $panel.closest('.row');
					$panel.parent('div').remove();
					if ( $row.children().length === 0 ) {
						$row.remove();
					}
				} else {
					$panel.remove();
				}
			});
	});

})( jQuery );

// Chart Circular
(function( $ ) {

	'use strict';

	if ( $.isFunction($.fn[ 'easyPieChart' ]) ) {

		$(function() {
			$('[data-plugin-chart-circular], .circular-bar-chart:not(.manual)').each(function() {
				var $this = $( this ),
					opts = {};

				var pluginOptions = $this.data('plugin-options');
				if (pluginOptions)
					opts = pluginOptions;

				$this.themePluginChartCircular(opts);
			});
		});

	}

}).apply(this, [ jQuery ]);

// Chart Circular
(function(theme, $) {

	theme = theme || {};

	var instanceName = '__chartCircular';

	var PluginChartCircular = function($el, opts) {
		return this.initialize($el, opts);
	};

	PluginChartCircular.defaults = {
		accX: 0,
		accY: -150,
		delay: 1,
		barColor: '#0088CC',
		trackColor: '#f2f2f2',
		scaleColor: false,
		scaleLength: 5,
		lineCap: 'round',
		lineWidth: 13,
		size: 175,
		rotate: 0,
		animate: ({
			duration: 2500,
			enabled: true
		})
	};

	PluginChartCircular.prototype = {
		initialize: function($el, opts) {
			if ( $el.data( instanceName ) ) {
				return this;
			}

			this.$el = $el;

			this
				.setData()
				.setOptions(opts)
				.build();

			return this;
		},

		setData: function() {
			this.$el.data(instanceName, this);

			return this;
		},

		setOptions: function(opts) {
			this.options = $.extend(true, {}, PluginChartCircular.defaults, opts, {
				wrapper: this.$el
			});

			return this;
		},

		build: function() {
			var self = this,
				$el = this.options.wrapper,
				value = ($el.attr('data-percent') ? $el.attr('data-percent') : 0),
				percentEl = $el.find('.percent'),
				shouldAnimate,
				data;

			shouldAnimate = $.isFunction($.fn[ 'appear' ]) && ( typeof $.browser !== 'undefined' && !$.browser.mobile );
			data = { accX: self.options.accX, accY: self.options.accY };

			$.extend(true, self.options, {
				onStep: function(from, to, currentValue) {
					percentEl.html(parseInt(currentValue));
				}
			});

			$el.attr('data-percent', (shouldAnimate ? 0 : value) );

			$el.easyPieChart( this.options );

			if ( shouldAnimate ) {
				$el.appear(function() {
					setTimeout(function() {
						$el.data('easyPieChart').update(value);
						$el.attr('data-percent', value);

					}, self.options.delay);
				}, data);
			} else {
				$el.data('easyPieChart').update(value);
				$el.attr('data-percent', value);
			}

			return this;
		}
	};

	// expose to scope
	$.extend(true, theme, {
		Chart: {
			PluginChartCircular: PluginChartCircular
		}
	});

	// jquery plugin
	$.fn.themePluginChartCircular = function(opts) {
		return this.map(function() {
			var $this = $(this);

			if ($this.data(instanceName)) {
				return $this.data(instanceName);
			} else {
				return new PluginChartCircular($this, opts);
			}

		});
	}

}).apply(this, [ window.theme, jQuery ]);

// Slider
(function( $ ) {

	'use strict';

	if ( $.isFunction($.fn[ 'slider' ]) ) {

		$(function() {
			$('[data-plugin-slider]').each(function() {
				var $this = $( this ),
					opts = {};

				var pluginOptions = $this.data('plugin-options');
				if (pluginOptions) {
					opts = pluginOptions;
				}

				$this.themePluginSlider(opts);
			});
		});

	}

}).apply(this, [ jQuery ]);

// Slider
(function(theme, $) {

	theme = theme || {};

	var instanceName = '__slider';

	var PluginSlider = function($el, opts) {
		return this.initialize($el, opts);
	};

	PluginSlider.defaults = {

	};

	PluginSlider.prototype = {
		initialize: function($el, opts) {
			if ( $el.data( instanceName ) ) {
				return this;
			}

			this.$el = $el;

			this
				.setVars()
				.setData()
				.setOptions(opts)
				.build();

			return this;
		},

		setVars: function() {
			var $output = $( this.$el.data('plugin-slider-output') );
			this.$output = $output.get(0) ? $output : null;

			return this;
		},

		setData: function() {
			this.$el.data(instanceName, this);

			return this;
		},

		setOptions: function(opts) {
			var _self = this;
			this.options = $.extend( true, {}, PluginSlider.defaults, opts );

			if ( this.$output ) {
				$.extend( this.options, {
					slide: function( event, ui ) {
						_self.onSlide( event, ui );
					}
				});
			}

			return this;
		},

		build: function() {
			this.$el.slider( this.options );

			return this;
		},

		onSlide: function( event, ui ) {
			if ( !ui.values ) {
				this.$output.val( ui.value );
			} else {
				this.$output.val( ui.values[ 0 ] + '/' + ui.values[ 1 ] );
			}

			this.$output.trigger('change');
		}
	};

	// expose to scope
	$.extend(theme, {
		PluginSlider: PluginSlider
	});

	// jquery plugin
	$.fn.themePluginSlider = function(opts) {
		return this.each(function() {
			var $this = $(this);

			if ($this.data(instanceName)) {
				return $this.data(instanceName);
			} else {
				return new PluginSlider($this, opts);
			}

		});
	}

}).apply(this, [ window.theme, jQuery ]);

// Data Tables - Config
(function($) {

	'use strict';

	// we overwrite initialize of all datatables here
	// because we want to use select2, give search input a bootstrap look
	// keep in mind if you overwrite this fnInitComplete somewhere,
	// you should run the code inside this function to keep functionality.
	//
	// there's no better way to do this at this time :(
	if ( $.isFunction( $.fn[ 'dataTable' ] ) ) {

		$.extend(true, $.fn.dataTable.defaults, {
			sDom: "<'row datatables-header form-inline'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>r><'table-responsive't><'row datatables-footer'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6'p>>",
			oLanguage: {
				sLengthMenu: '_MENU_ records per page',
				sProcessing: '<i class="fa fa-spinner fa-spin"></i> Loading'
			},
			fnInitComplete: function( settings, json ) {
				// select 2
				if ( $.isFunction( $.fn[ 'select2' ] ) ) {
					$('.dataTables_length select', settings.nTableWrapper).select2({
						minimumResultsForSearch: -1
					});
				}

				var options = $( 'table', settings.nTableWrapper ).data( 'plugin-options' ) || {};

				// search
				var $search = $('.dataTables_filter input', settings.nTableWrapper);

				$search
					.attr({
						placeholder: typeof options.searchPlaceholder !== 'undefined' ? options.searchPlaceholder : 'Search'
					})
					.addClass('form-control');

				if ( $.isFunction( $.fn.placeholder ) ) {
					$search.placeholder();
				}
			}
		});

	}

}).apply( this, [ jQuery ]);

// Codemirror
(function( $ ) {

	'use strict';

	if ( typeof CodeMirror !== 'undefined' ) {

		$(function() {
			$('[data-plugin-codemirror]').each(function() {
				var $this = $( this ),
					opts = {};

				var pluginOptions = $this.data('plugin-options');
				if (pluginOptions)
					opts = pluginOptions;

				$this.themePluginCodeMirror(opts);
			});
		});

	}

}).apply(this, [ jQuery ]);

// Codemirror
(function(theme, $) {

	theme = theme || {};

	var instanceName = '__codemirror';

	var PluginCodeMirror = function($el, opts) {
		return this.initialize($el, opts);
	};

	PluginCodeMirror.defaults = {
		lineNumbers: true,
		styleActiveLine: true,
		matchBrackets: true,
		theme: 'monokai'
	};

	PluginCodeMirror.prototype = {
		initialize: function($el, opts) {
			if ( $el.data( instanceName ) ) {
				return this;
			}

			this.$el = $el;

			this
				.setData()
				.setOptions(opts)
				.build();

			return this;
		},

		setData: function() {
			this.$el.data(instanceName, this);

			return this;
		},

		setOptions: function(opts) {
			this.options = $.extend( true, {}, PluginCodeMirror.defaults, opts );

			return this;
		},

		build: function() {
			CodeMirror.fromTextArea( this.$el.get(0), this.options );

			return this;
		}
	};

	// expose to scope
	$.extend(theme, {
		PluginCodeMirror: PluginCodeMirror
	});

	// jquery plugin
	$.fn.themePluginCodeMirror = function(opts) {
		return this.each(function() {
			var $this = $(this);

			if ($this.data(instanceName)) {
				return $this.data(instanceName);
			} else {
				return new PluginCodeMirror($this, opts);
			}

		});
	}

}).apply(this, [ window.theme, jQuery ]);

// Colorpicker
(function( $ ) {

	'use strict';

	if ( $.isFunction($.fn[ 'colorpicker' ]) ) {

		$(function() {
			$('[data-plugin-colorpicker]').each(function() {
				var $this = $( this ),
					opts = {};

				var pluginOptions = $this.data('plugin-options');
				if (pluginOptions)
					opts = pluginOptions;

				$this.themePluginColorPicker(opts);
			});
		});

	}

}).apply(this, [ jQuery ]);

// Colorpicker
(function(theme, $) {

	theme = theme || {};

	var instanceName = '__colorpicker';

	var PluginColorPicker = function($el, opts) {
		return this.initialize($el, opts);
	};

	PluginColorPicker.defaults = {
	};

	PluginColorPicker.prototype = {
		initialize: function($el, opts) {
			if ( $el.data( instanceName ) ) {
				return this;
			}

			this.$el = $el;

			this
				.setData()
				.setOptions(opts)
				.build();

			return this;
		},

		setData: function() {
			this.$el.data(instanceName, this);

			return this;
		},

		setOptions: function(opts) {
			this.options = $.extend( true, {}, PluginColorPicker.defaults, opts );

			return this;
		},

		build: function() {
			this.$el.colorpicker( this.options );

			return this;
		}
	};

	// expose to scope
	$.extend(theme, {
		PluginColorPicker: PluginColorPicker
	});

	// jquery plugin
	$.fn.themePluginColorPicker = function(opts) {
		return this.each(function() {
			var $this = $(this);

			if ($this.data(instanceName)) {
				return $this.data(instanceName);
			} else {
				return new PluginColorPicker($this, opts);
			}

		});
	}

}).apply(this, [ window.theme, jQuery ]);

// Datepicker
(function( $ ) {

	'use strict';

	if ( $.isFunction($.fn[ 'datepicker' ]) ) {

		$(function() {
			$('[data-plugin-datepicker]').each(function() {
				var $this = $( this ),
					opts = {};

				var pluginOptions = $this.data('plugin-options');
				if (pluginOptions)
					opts = pluginOptions;

				$this.themePluginDatePicker(opts);
			});
		});

	}

}).apply(this, [ jQuery ]);

// Datepicker
(function(theme, $) {

	theme = theme || {};

	var instanceName = '__datepicker';

	var PluginDatePicker = function($el, opts) {
		return this.initialize($el, opts);
	};

	PluginDatePicker.defaults = {
	};

	PluginDatePicker.prototype = {
		initialize: function($el, opts) {
			if ( $el.data( instanceName ) ) {
				return this;
			}

			this.$el = $el;

			this
				.setVars()
				.setData()
				.setOptions(opts)
				.build();

			return this;
		},

		setVars: function() {
			this.skin = this.$el.data( 'plugin-skin' );

			return this;
		},

		setData: function() {
			this.$el.data(instanceName, this);

			return this;
		},

		setOptions: function(opts) {
			this.options = $.extend( true, {}, PluginDatePicker.defaults, opts );

			return this;
		},

		build: function() {
			this.$el.datepicker( this.options );

			if ( !!this.skin ) {
				this.$el.data('datepicker').picker.addClass( 'datepicker-' + this.skin );
			}

			return this;
		}
	};

	// expose to scope
	$.extend(theme, {
		PluginDatePicker: PluginDatePicker
	});

	// jquery plugin
	$.fn.themePluginDatePicker = function(opts) {
		return this.each(function() {
			var $this = $(this);

			if ($this.data(instanceName)) {
				return $this.data(instanceName);
			} else {
				return new PluginDatePicker($this, opts);
			}

		});
	}

}).apply(this, [ window.theme, jQuery ]);

// iosSwitcher
(function( $ ) {

	'use strict';

	if ( typeof Switch !== 'undefined' && $.isFunction( Switch ) ) {

		$(function() {
			$('[data-plugin-ios-switch]').each(function() {
				var $this = $( this );

				$this.themePluginIOS7Switch();
			});
		});

	}

}).apply(this, [ jQuery ]);

// iosSwitcher
(function(theme, $) {

	theme = theme || {};

	var instanceName = '__IOS7Switch';

	var PluginIOS7Switch = function($el) {
		return this.initialize($el);
	};

	PluginIOS7Switch.prototype = {
		initialize: function($el) {
			if ( $el.data( instanceName ) ) {
				return this;
			}

			this.$el = $el;

			this
				.setData()
				.build();

			return this;
		},

		setData: function() {
			this.$el.data(instanceName, this);

			return this;
		},

		build: function() {
			var switcher = new Switch( this.$el.get(0) );

			$( switcher.el ).on( 'click', function( e ) {
				e.preventDefault();
				switcher.toggle();
			});

			return this;
		}
	};

	// expose to scope
	$.extend(theme, {
		PluginIOS7Switch: PluginIOS7Switch
	});

	// jquery plugin
	$.fn.themePluginIOS7Switch = function(opts) {
		return this.each(function() {
			var $this = $(this);

			if ($this.data(instanceName)) {
				return $this.data(instanceName);
			} else {
				return new PluginIOS7Switch($this);
			}

		});
	}

}).apply(this, [ window.theme, jQuery ]);

// Markdown
(function( $ ) {

	'use strict';

	if ( $.isFunction($.fn[ 'markdown' ]) ) {

		$(function() {
			$('[data-plugin-markdown-editor]').each(function() {
				var $this = $( this ),
					opts = {};

				var pluginOptions = $this.data('plugin-options');
				if (pluginOptions)
					opts = pluginOptions;

				$this.themePluginMarkdownEditor(opts);
			});
		});

	}

}).apply(this, [ jQuery ]);

// Markdown
(function(theme, $) {

	theme = theme || {};

	var instanceName = '__markdownEditor';

	var PluginMarkdownEditor = function($el, opts) {
		return this.initialize($el, opts);
	};

	PluginMarkdownEditor.defaults = {
		iconlibrary: 'fa'
	};

	PluginMarkdownEditor.prototype = {
		initialize: function($el, opts) {
			if ( $el.data( instanceName ) ) {
				return this;
			}

			this.$el = $el;

			this
				.setData()
				.setOptions(opts)
				.build();

			return this;
		},

		setData: function() {
			this.$el.data(instanceName, this);

			return this;
		},

		setOptions: function(opts) {
			this.options = $.extend( true, {}, PluginMarkdownEditor.defaults, opts );

			return this;
		},

		build: function() {
			this.$el.markdown( this.options );

			return this;
		}
	};

	// expose to scope
	$.extend(theme, {
		PluginMarkdownEditor: PluginMarkdownEditor
	});

	// jquery plugin
	$.fn.themePluginMarkdownEditor = function(opts) {
		return this.each(function() {
			var $this = $(this);

			if ($this.data(instanceName)) {
				return $this.data(instanceName);
			} else {
				return new PluginMarkdownEditor($this, opts);
			}

		});
	}

}).apply(this, [ window.theme, jQuery ]);

// Masked Input
(function( $ ) {

	'use strict';

	if ( $.isFunction($.fn[ 'mask' ]) ) {

		$(function() {
			$('[data-plugin-masked-input]').each(function() {
				var $this = $( this ),
					opts = {};

				var pluginOptions = $this.data('plugin-options');
				if (pluginOptions)
					opts = pluginOptions;

				$this.themePluginMaskedInput(opts);
			});
		});

	}

}).apply(this, [ jQuery ]);

// Masked Input
(function(theme, $) {

	theme = theme || {};

	var instanceName = '__maskedInput';

	var PluginMaskedInput = function($el, opts) {
		return this.initialize($el, opts);
	};

	PluginMaskedInput.defaults = {
	};

	PluginMaskedInput.prototype = {
		initialize: function($el, opts) {
			if ( $el.data( instanceName ) ) {
				return this;
			}

			this.$el = $el;

			this
				.setData()
				.setOptions(opts)
				.build();

			return this;
		},

		setData: function() {
			this.$el.data(instanceName, this);

			return this;
		},

		setOptions: function(opts) {
			this.options = $.extend( true, {}, PluginMaskedInput.defaults, opts );

			return this;
		},

		build: function() {
			this.$el.mask( this.$el.data('input-mask'), this.options );

			return this;
		}
	};

	// expose to scope
	$.extend(theme, {
		PluginMaskedInput: PluginMaskedInput
	});

	// jquery plugin
	$.fn.themePluginMaskedInput = function(opts) {
		return this.each(function() {
			var $this = $(this);

			if ($this.data(instanceName)) {
				return $this.data(instanceName);
			} else {
				return new PluginMaskedInput($this, opts);
			}

		});
	}

}).apply(this, [ window.theme, jQuery ]);

// MaxLength
(function( $ ) {

	'use strict';

	if ( $.isFunction( $.fn[ 'maxlength' ]) ) {

		$(function() {
			$('[data-plugin-maxlength]').each(function() {
				var $this = $( this ),
					opts = {};

				var pluginOptions = $this.data('plugin-options');
				if (pluginOptions)
					opts = pluginOptions;

				$this.themePluginMaxLength(opts);
			});
		});

	}

}).apply(this, [ jQuery ]);

// MaxLength
(function(theme, $) {

	theme = theme || {};

	var instanceName = '__maxlength';

	var PluginMaxLength = function($el, opts) {
		return this.initialize($el, opts);
	};

	PluginMaxLength.defaults = {
		alwaysShow: true,
		placement: 'bottom-left',
		warningClass: 'label label-success bottom-left',
		limitReachedClass: 'label label-danger bottom-left'
	};

	PluginMaxLength.prototype = {
		initialize: function($el, opts) {
			if ( $el.data( instanceName ) ) {
				return this;
			}

			this.$el = $el;

			this
				.setData()
				.setOptions(opts)
				.build();

			return this;
		},

		setData: function() {
			this.$el.data(instanceName, this);

			return this;
		},

		setOptions: function(opts) {
			this.options = $.extend( true, {}, PluginMaxLength.defaults, opts );

			return this;
		},

		build: function() {
			this.$el.maxlength( this.options );

			return this;
		}
	};

	// expose to scope
	$.extend(theme, {
		PluginMaxLength: PluginMaxLength
	});

	// jquery plugin
	$.fn.themePluginMaxLength = function(opts) {
		return this.each(function() {
			var $this = $(this);

			if ($this.data(instanceName)) {
				return $this.data(instanceName);
			} else {
				return new PluginMaxLength($this, opts);
			}

		});
	}

}).apply(this, [ window.theme, jQuery ]);

// MultiSelect
(function( $ ) {

	'use strict';

	if ( $.isFunction( $.fn[ 'multiselect' ] ) ) {

		$(function() {
			$( '[data-plugin-multiselect]' ).each(function() {

				var $this = $( this ),
					opts = {};

				var pluginOptions = $this.data('plugin-options');
				if (pluginOptions)
					opts = pluginOptions;

				$this.themePluginMultiSelect(opts);

			});
		});

	}

}).apply( this, [ jQuery ]);

// MultiSelect
(function(theme, $) {

	theme = theme || {};

	var instanceName = '__multiselect';

	var PluginMultiSelect = function($el, opts) {
		return this.initialize($el, opts);
	};

	PluginMultiSelect.defaults = {
		templates: {
			filter: '<div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input class="form-control multiselect-search" type="text"></div>'
		}
	};

	PluginMultiSelect.prototype = {
		initialize: function($el, opts) {
			if ( $el.data( instanceName ) ) {
				return this;
			}

			this.$el = $el;

			this
				.setData()
				.setOptions(opts)
				.build();

			return this;
		},

		setData: function() {
			this.$el.data(instanceName, this);

			return this;
		},

		setOptions: function(opts) {
			this.options = $.extend( true, {}, PluginMultiSelect.defaults, opts );

			return this;
		},

		build: function() {
			this.$el.multiselect( this.options );

			return this;
		}
	};

	// expose to scope
	$.extend(theme, {
		PluginMultiSelect: PluginMultiSelect
	});

	// jquery plugin
	$.fn.themePluginMultiSelect = function(opts) {
		return this.each(function() {
			var $this = $(this);

			if ($this.data(instanceName)) {
				return $this.data(instanceName);
			} else {
				return new PluginMultiSelect($this, opts);
			}

		});
	}

}).apply(this, [ window.theme, jQuery ]);

(function( $ ) {

	'use strict';

	if ( $.isFunction( $.fn[ 'placeholder' ]) ) {

		$('input[placeholder]').placeholder();

	}

}).apply(this, [ jQuery ]);


// Select2
(function( $ ) {

	'use strict';

	if ( $.isFunction($.fn[ 'select2' ]) ) {

		$(function() {
			$('[data-plugin-selectTwo]').each(function() {
				var $this = $( this ),
					opts = {};

				var pluginOptions = $this.data('plugin-options');
				if (pluginOptions)
					opts = pluginOptions;

				$this.themePluginSelect2(opts);
			});
		});

	}

}).apply(this, [ jQuery ]);

// Select2
(function(theme, $) {

	theme = theme || {};

	var instanceName = '__select2';

	var PluginSelect2 = function($el, opts) {
		return this.initialize($el, opts);
	};

	PluginSelect2.defaults = {
	};

	PluginSelect2.prototype = {
		initialize: function($el, opts) {
			if ( $el.data( instanceName ) ) {
				return this;
			}

			this.$el = $el;

			this
				.setData()
				.setOptions(opts)
				.build();

			return this;
		},

		setData: function() {
			this.$el.data(instanceName, this);

			return this;
		},

		setOptions: function(opts) {
			this.options = $.extend( true, {}, PluginSelect2.defaults, opts );

			return this;
		},

		build: function() {
			this.$el.select2( this.options );

			return this;
		}
	};

	// expose to scope
	$.extend(theme, {
		PluginSelect2: PluginSelect2
	});

	// jquery plugin
	$.fn.themePluginSelect2 = function(opts) {
		return this.each(function() {
			var $this = $(this);

			if ($this.data(instanceName)) {
				return $this.data(instanceName);
			} else {
				return new PluginSelect2($this, opts);
			}

		});
	}

}).apply(this, [ window.theme, jQuery ]);

// Spinner
(function( $ ) {

	'use strict';

	if ( $.isFunction($.fn[ 'spinner' ]) ) {

		$(function() {
			$('[data-plugin-spinner]').each(function() {
				var $this = $( this ),
					opts = {};

				var pluginOptions = $this.data('plugin-options');
				if (pluginOptions)
					opts = pluginOptions;

				$this.themePluginSpinner(opts);
			});
		});

	}

}).apply(this, [ jQuery ]);

// Spinner
(function(theme, $) {

	theme = theme || {};

	var instanceName = '__spinner';

	var PluginSpinner = function($el, opts) {
		return this.initialize($el, opts);
	};

	PluginSpinner.defaults = {
	};

	PluginSpinner.prototype = {
		initialize: function($el, opts) {
			if ( $el.data( instanceName ) ) {
				return this;
			}

			this.$el = $el;

			this
				.setData()
				.setOptions(opts)
				.build();

			return this;
		},

		setData: function() {
			this.$el.data(instanceName, this);

			return this;
		},

		setOptions: function(opts) {
			this.options = $.extend( true, {}, PluginSpinner.defaults, opts );

			return this;
		},

		build: function() {
			this.$el.spinner( this.options );

			return this;
		}
	};

	// expose to scope
	$.extend(theme, {
		PluginSpinner: PluginSpinner
	});

	// jquery plugin
	$.fn.themePluginSpinner = function(opts) {
		return this.each(function() {
			var $this = $(this);

			if ($this.data(instanceName)) {
				return $this.data(instanceName);
			} else {
				return new PluginSpinner($this, opts);
			}

		});
	}

}).apply(this, [ window.theme, jQuery ]);

// SummerNote
(function( $ ) {

	'use strict';

	if ( $.isFunction($.fn[ 'summernote' ]) ) {

		$(function() {
			$('[data-plugin-summernote]').each(function() {
				var $this = $( this ),
					opts = {};

				var pluginOptions = $this.data('plugin-options');
				if (pluginOptions)
					opts = pluginOptions;

				$this.themePluginSummerNote(opts);
			});
		});

	}

}).apply(this, [ jQuery ]);

// SummerNote
(function(theme, $) {

	theme = theme || {};

	var instanceName = '__summernote';

	var PluginSummerNote = function($el, opts) {
		return this.initialize($el, opts);
	};

	PluginSummerNote.defaults = {
		onfocus: function() {
			$( this ).closest( '.note-editor' ).addClass( 'active' );
		},
		onblur: function() {
			$( this ).closest( '.note-editor' ).removeClass( 'active' );
		}
	};

	PluginSummerNote.prototype = {
		initialize: function($el, opts) {
			if ( $el.data( instanceName ) ) {
				return this;
			}

			this.$el = $el;

			this
				.setData()
				.setOptions(opts)
				.build();

			return this;
		},

		setData: function() {
			this.$el.data(instanceName, this);

			return this;
		},

		setOptions: function(opts) {
			this.options = $.extend( true, {}, PluginSummerNote.defaults, opts );

			return this;
		},

		build: function() {
			this.$el.summernote( this.options );

			return this;
		}
	};

	// expose to scope
	$.extend(theme, {
		PluginSummerNote: PluginSummerNote
	});

	// jquery plugin
	$.fn.themePluginSummerNote = function(opts) {
		return this.each(function() {
			var $this = $(this);

			if ($this.data(instanceName)) {
				return $this.data(instanceName);
			} else {
				return new PluginSummerNote($this, opts);
			}

		});
	}

}).apply(this, [ window.theme, jQuery ]);

// TextArea AutoSize
(function( $ ) {

	'use strict';

	if ( $.isFunction($.fn[ 'autosize' ]) ) {

		$(function() {
			$('[data-plugin-textarea-autosize]').each(function() {
				var $this = $( this ),
					opts = {};

				var pluginOptions = $this.data('plugin-options');
				if (pluginOptions)
					opts = pluginOptions;

				$this.themePluginTextAreaAutoSize(opts);
			});
		});

	}

}).apply(this, [ jQuery ]);

// TextArea AutoSize
(function(theme, $) {

	theme = theme || {};

	var initialized = false;
	var instanceName = '__textareaAutosize';

	var PluginTextAreaAutoSize = function($el, opts) {
		return this.initialize($el, opts);
	};

	PluginTextAreaAutoSize.defaults = {
	};

	PluginTextAreaAutoSize.prototype = {
		initialize: function($el, opts) {
			if (initialized) {
				return this;
			}

			this.$el = $el;

			this
				.setData()
				.setOptions(opts)
				.build();

			return this;
		},

		setData: function() {
			this.$el.data(instanceName, this);

			return this;
		},

		setOptions: function(opts) {
			this.options = $.extend( true, {}, PluginTextAreaAutoSize.defaults, opts );

			return this;
		},

		build: function() {
			this.$el.autosize( this.options );

			return this;
		}
	};

	// expose to scope
	$.extend(theme, {
		PluginTextAreaAutoSize: PluginTextAreaAutoSize
	});

	// jquery plugin
	$.fn.themePluginTextAreaAutoSize = function(opts) {
		return this.each(function() {
			var $this = $(this);

			if ($this.data(instanceName)) {
				return $this.data(instanceName);
			} else {
				return new PluginTextAreaAutoSize($this, opts);
			}

		});
	}

}).apply(this, [ window.theme, jQuery ]);

// TimePicker
(function( $ ) {

	'use strict';

	if ( $.isFunction($.fn[ 'timepicker' ]) ) {

		$(function() {
			$('[data-plugin-timepicker]').each(function() {
				var $this = $( this ),
					opts = {};

				var pluginOptions = $this.data('plugin-options');
				if (pluginOptions)
					opts = pluginOptions;

				$this.themePluginTimePicker(opts);
			});
		});

	}

}).apply(this, [ jQuery ]);

// TimePicker
(function(theme, $) {

	theme = theme || {};

	var instanceName = '__timepicker';

	var PluginTimePicker = function($el, opts) {
		return this.initialize($el, opts);
	};

	PluginTimePicker.defaults = {
		disableMousewheel: true
	};

	PluginTimePicker.prototype = {
		initialize: function($el, opts) {
			if ( $el.data( instanceName ) ) {
				return this;
			}

			this.$el = $el;

			this
				.setData()
				.setOptions(opts)
				.build();

			return this;
		},

		setData: function() {
			this.$el.data(instanceName, this);

			return this;
		},

		setOptions: function(opts) {
			this.options = $.extend( true, {}, PluginTimePicker.defaults, opts );

			return this;
		},

		build: function() {
			this.$el.timepicker( this.options );

			return this;
		}
	};

	// expose to scope
	$.extend(theme, {
		PluginTimePicker: PluginTimePicker
	});

	// jquery plugin
	$.fn.themePluginTimePicker = function(opts) {
		return this.each(function() {
			var $this = $(this);

			if ($this.data(instanceName)) {
				return $this.data(instanceName);
			} else {
				return new PluginTimePicker($this, opts);
			}

		});
	}

}).apply(this, [ window.theme, jQuery ]);