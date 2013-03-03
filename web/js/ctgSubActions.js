var CtgActionsBar = new Class({
	actionsEl : null,
	
	currCtgActions : null,
	
	initialize : function(){
		var _this = this;
		
		var ctgSubActions = $$('.ctgSubActions');
		if (0 in ctgSubActions){
			this.actionsEl = ctgSubActions[0];
			
			var tdCtgSubs = $$('.tdCtgSub');
			tdCtgSubs.each(function(tdCtgSub){
				var ctgAction = new CtgActions(tdCtgSub, _this);
			});
			
			this.setEvents();
		}
	},
	
	setEvents : function(){
		var ctgSubActionDelete = this.actionsEl.getElement('.ctgSubActionDelete');
		if (ctgSubActionDelete){
			ctgSubActionDelete.addEvent('click', this.deleteAction.bind(this));
		}
		
		var ctgSubActionRevive = this.actionsEl.getElement('.ctgSubActionRevive');
		if (ctgSubActionRevive){
			ctgSubActionRevive.addEvent('click', this.reviveAction.bind(this));
		}
		
		var ctgSubActionEdit = this.actionsEl.getElement('.ctgSubActionEdit');
		if (ctgSubActionEdit){
			ctgSubActionEdit.addEvent('click', this.editAction.bind(this));
		}
		
		var ctgSubActionAdd = this.actionsEl.getElement('.ctgSubActionAdd');
		if (ctgSubActionAdd){
			ctgSubActionAdd.addEvent('click', this.addAction.bind(this));
		}
	},
	
	deleteAction : function(e){
		e.stop();

		if (this.currCtgActions && confirm('DELETE this CATEGORY?')){
			this.currCtgActions.deleteAction();
		}
	},
	
	reviveAction : function(e){
		e.stop();
		if (this.currCtgActions && confirm('REVIVE this CATEGORY?')){
			this.currCtgActions.reviveAction();
		}
	},
	
	editAction : function(){
		//e.stop();
		if (this.currCtgActions){
			this.currCtgActions.editAction();
		}
	},
	
	addAction : function(){
		if (this.currCtgActions){
			this.currCtgActions.addAction();
		}
	},
	
	setCurrCtgActions : function(ctgActions){
		this.currCtgActions = ctgActions;
	},
	
	show : function(ctgActions, coord, deleted){
		this.setCurrCtgActions(ctgActions);
		
		this.actionsEl.setStyles({
			left : coord.right + 10,
			top: coord.top
		});
		
		var ctgSubActionDelete = this.actionsEl.getElement('.ctgSubActionDelete');
		var ctgSubActionRevive = this.actionsEl.getElement('.ctgSubActionRevive');
		
		if (deleted){
			ctgSubActionDelete.addClass('hidden');
			ctgSubActionRevive.removeClass('hidden');
		} else {
			ctgSubActionDelete.removeClass('hidden');
			ctgSubActionRevive.addClass('hidden');
		}
		
		this.actionsEl.removeClass('hidden');
	}
});

var CtgActions = new Class({
	ctgId : null,
	el : null,
	ctgActionsBar : null,
	
	initialize : function(el, ctgActionsBar){
		if (el){
			var _this = this;
			this.el = el;
			
			this.ctgId = this.el.get('ctgId');

			this.ctgActionsBar = ctgActionsBar;
			this.el.addEvent('mouseenter', this.show.bind(this));
		}
	},
	
	show : function(){
		var linkCtg = this.el.getElement('.linkCtg');
		if (linkCtg){
			var coord = linkCtg.getCoordinates();
			var deleted = linkCtg.hasClass('tdCtgSub_deleted');
			
			this.ctgActionsBar.show(this, coord, deleted);
		}
	},
	
	addAction : function(){
		var ctgSubActionAdd = this.ctgActionsBar.actionsEl.getElement('.ctgSubActionAdd');
		if (ctgSubActionAdd){
			ctgSubActionAdd.set('href', '/category/new?id=' + this.ctgId);
		}
	},
	
	editAction : function(){
		var ctgSubActionEdit = this.ctgActionsBar.actionsEl.getElement('.ctgSubActionEdit');
		if (ctgSubActionEdit){
			ctgSubActionEdit.set('href', '/category/' + this.ctgId + '/edit');
		}
	},
	
	deleteAction : function(){
		var _this = this;
		
		var req = new Request({
			url : '/category/delete',
			onSuccess : function(res){
				var res = JSON.decode(res);
				if (res.status){
					var linkCtg = _this.el.getElement('.linkCtg');
					if (linkCtg){
						linkCtg.addClass('tdCtgSub_deleted');
						_this.show();
					}
					
				}
			}
		}).post('f=JSON&id=' + _this.ctgId);

	},
	
	reviveAction : function(){
		var _this = this;
		
		var req = new Request({
			url : '/category/revive',
			onSuccess : function(res){
				var res = JSON.decode(res);
				if (res.status){
					var linkCtg = _this.el.getElement('.linkCtg');
					if (linkCtg){
						linkCtg.removeClass('tdCtgSub_deleted');
						_this.show();
					}
				}
			}
		}).post('f=JSON&id=' + _this.ctgId);
	}
});

window.addEvent('domready', function(){
	var ctgActions = new CtgActionsBar();
});

