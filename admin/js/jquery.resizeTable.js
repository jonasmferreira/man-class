(function ($) {

	$.fn.resizeTable = function(o){
		options = {
			minHeight: 50,
			maxHeight: false,
			bindToWindowResize: true,
			padding: 47,
			striped: true,
			evenClass: "even",
			oddClass: "odd",
			addMouseOverEffect: true,
			trOverColor: "rgba(170, 204, 221, 0.35)",
			lineHeight: 20,
			debugMode: false,
			autoAlign: true,
			tr2look: 0,
			autoResize: true,
			addBorder: true
		};


		jQuery.extend(options, o);

		$tables = $(this);
		$tables.each(function(){
			$currentTable = $(this);
			if(!jQuery.browser.msie){


			if(options.striped == true)
				stripTable($currentTable, options);

			if(options.autoResize == true)
				resizeTable(this, options);

			if(options.bindToWindowResize == true){
				$(window).bind("resize", function(){
					if(options.striped == true)
						stripTable($currentTable, options);

					if(options.addMouseOverEffect == true)
						addTrOverEffect($currentTable, options);

					if(options.autoResize == true)
						resizeTable($currentTable, options);

				});
			}

			if(options.addMouseOverEffect == true)
				addTrOverEffect($currentTable, options);

			if(options.autoAlign == true)
				adjustAlign($currentTable, options, options.customAlign);

			if(options.addBorder == true)
				$currentTable.css({
					border: "1px solid #CCC"
				});
			}else{
				if(options.addBorder == true){
					$currentTable.css({
						border: "1px solid #CCC"
					});
				}
				if(options.striped == true){
					stripTable($currentTable, options);
				}
			}
		});

		return $tables;
	};

	function resizeTable(table, options){
		windowHeight = parseInt($(window).height());
		marginTop = parseInt($(table).offset().top);
		if($(table).find("tfoot").length > 0)
			footerSize = parseInt($(table).find("tfoot:first").height());
		else
			footerSize = 0;

		newHeight = windowHeight - footerSize - marginTop - options.padding;

		if(options.debugMode == true){
			console.log("windowHeight: " + windowHeight
					+"\nMarginTop: " + marginTop
					+"\nFooterSize: " + footerSize
					+"\nNewHeight: " + newHeight);
		}
		if(newHeight < options.minHeight)
				newHeight = options.minHeight;

		if(options.maxHeight != false){
			if(newHeight > options.maxHeight)
				newHeight = options.maxHeight;
		}

		var tamanhoAcumulado = 0;
		$(table).find("tbody:first tr").each(function(){
			tamanhoAcumulado += parseInt($(this).height());
		});

		if(tamanhoAcumulado > newHeight){
			$(table).find("tbody:first").css({
				"overflow-y": 'auto',
				"overflow-x": 'hidden'
			}).height(newHeight);
		} else {
			$(table).find("tbody:first").css({
				"overflow-y": 'visible',
				"overflow-x": 'hidden'
			}).height("auto");
		}
	}

	function stripTable(table, options){
		$(table).find("tr").removeClass(options.evenClass);
		$(table).find("tbody:first tr:even").addClass("even");

	}

	function addTrOverEffect(table, options){
		$(table).find("tbody:first tr").each(function(){

			if($(this).hasClass(options.emptyLineClass)) return;

			$(this).unbind("mouseover").bind("mouseover", function(){
				$(this).addClass('ui-state-hover');
			}).unbind("mouseout").bind("mouseout", function(){
				$(this).removeClass('ui-state-hover');
			});
		});
	}

	function adjustAlign(table, options, customAlign){
		var align = new Array();
		var brDatePattern = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/gi;
		var brDateHourPattern = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4} [0-9]{2}\:[0-9]{2}(\:[0-9]{2})?$/gi;
		var dbDatePattern = /^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/gi;
		var hourPattern = /^[0-9]{2}\:[0-9]{2}\:[0-9]{2]$/gi;
		table.find("tbody:first tr:eq(" + options.tr2look + ") td").each(function(eq){

			if(jQuery(this).parent().hasClass(options.emptyLineClass))
				return;

			if(jQuery.trim(jQuery(this).attr('align')) == "" || jQuery(this).attr('align') == undefined){
				var text = jQuery.trim(jQuery(this).text());
				if(text.match(brDatePattern) || text.match(dbDatePattern) || text.match(brDateHourPattern) || text.match(hourPattern)){
					align[eq] = 'center';
				} else if(! isNaN(text.replace('.', '').replace(',', '.'))){
					align[eq] = 'right';
				} else if(typeof(text.replace(/\,|\./gi, '')) == 'number'){
					align[eq] = 'right';
				} else {
					align[eq] = 'left';
				}
			} else {
				align[eq] = jQuery(this).attr('align');
			}
		});

		table.find("tbody:first tr").each(function(){
			if(jQuery(this).hasClass(options.emptyLineClass))
				return;

			jQuery(this).find("td").each(function(eq){
				jQuery(this).attr("align", align[eq]);
			});
		});
	}

})(jQuery);