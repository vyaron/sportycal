var InfoBubble = new Class({
	Implements: Options,
	
	options: {
		mobile : false,
		pos : 'left',
		width : 400,
		height : 300,
        content : ''
    },
    
    el : null,
    
    elFx : null,
    
    iconEl : null,
    
	initialize : function(iconEl, options){
		this.setOptions(options);
		
		this.el = $('infoBubble');
		this.elFx = new Fx.Tween(this.el, {
			property : 'opacity'
		});
		this.elFx.set(0);
		this.el.removeClass('hidden');
		
		if (iconEl){
			this.iconEl = iconEl;
			this.iconEl.addEvent('click', this.show.bind(this));
			
			this.setContent();
		}
		
		var infoBubbleClose = $('infoBubbleClose');
		if (infoBubbleClose) {
			infoBubbleClose.addEvent('click', this.hide.bind(this));
		}
	},
	
	setContent : function(){
		var itemId = this.iconEl.getProperty('itemId');
		var isTextDesc = this.iconEl.hasClass('eventTextIcon');
		var isHtmlDesc = this.iconEl.hasClass('eventHtmlIcon');
		
		
		if (isTextDesc){
			if (gMobilePopupItems && itemId in gMobilePopupItems){
				this.options.content = gMobilePopupItems[itemId].notes;
			} else {
				this.iconEl.addClass('hide');
			}
		} else if (isHtmlDesc){
			if (gHtmlPreivews && itemId in gHtmlPreivews){
				this.options.content = gHtmlPreivews[itemId].notes;
			} else {
				this.iconEl.addClass('hide');
			}
		} else {
			var content = this.iconEl.getElement('div');
			if (content){
				this.options.content = content.get('html');
			}
		}
	},
	
	hide : function(e){
		if (e){
			e.stop();
		}
		this.elFx.start(0);
	},
	
	show : function(e){
		if (e){
			e.stop();
		}
		
		var contentEl = this.el.getElement('#infoBubbleContent');
		
		if (!this.options.mobile){
			var margin = 20;
			
			var pos = this.iconEl.getPosition();
			
			
			if (this.options.pos == 'right'){
				var left = pos.x - (this.options.width + margin);
			} else {
				var left = pos.x + margin;
			}
		
		
			var styles = {
				'width' : this.options.width,
				'height' : this.options.height,
				'top' : (pos.y + margin),
				'left' : left
			};

			this.el.setStyles(styles);
			
			contentEl.setStyle('height', this.options.height - 65);
		}
		
		contentEl.set('html', this.options.content);

		this.elFx.start(1);
	}
});