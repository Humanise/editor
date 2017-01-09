var controller = {
	pageId : null,

	$ready : function() {
		hui.ui.tellContainers('changeSelection','service:preview');
		this._refreshBase();
	},
	$pageLoaded : function(id) {
		this.pageId = id;
		this._updateState();
		this._refreshBase();
		this.$click$cancelNote();
		reviewPanel.hide();
	},
	_refreshBase : function() {
		hui.ui.tellContainers('refreshBase');
	},
	_updateState : function() {
		hui.ui.request({
			url : 'data/LoadPageStatus.php',
			parameters : {id:this.pageId},
			$object : function(obj) {
				publish.setEnabled(obj.changed);
				var overlays = {none:null,accepted:'success',rejected:'stop'};
				review.setOverlay(overlays[obj.review]);
			}
		});
	},
	$pageChanged : function() {
		publish.setEnabled(true);
	},

	$click$close : function() {
		this.getFrame().location='../../Tools/Sites/';
	},
	$click$edit : function() {
		var frame = this.getFrame();
		if (frame.templateController!==undefined) {
			frame.templateController.edit();
		} else {
			document.location='../../Template/Edit.php';
		}
	},
	$click$properties : function() {
		this.getFrame().op.Editor.editProperties(hui.ui.language);
	},
	$click$view : function() {
		window.parent.location='ViewPublished.php';
	},
	$click$publish : function() {
		hui.ui.request({
			url : 'viewer/data/PublishPage.php',
			parameters : {id:this.pageId},
			$success : function(obj) {
				publish.setEnabled(false);
				hui.ui.tellContainers('pageChanged',this.pageId);
			}
		});
	},
	getFrame : function() {
		return hui.ui.get('simulator').getFrameWindow();
	},
	$click$viewHistory : function() {
		this.getFrame().location = '../PageHistory/';
	},

	///////////// Design /////////////

	$click$design : function() {
		this.getFrame().op.Editor.editDesign();
	},

	///////////// Notes //////////////

	$click$addNote : function() {
		notePanel.show();
		noteFormula.focus();
	},
	$submit$noteFormula : function(form) {
		var values = form.getValues();
		hui.ui.request({
			message : {start:'Gemmer note...',delay:300},
			url : 'data/CreateNote.php',
			parameters : {pageId : this.pageId, text : values.text, kind : values.kind},
			$success : function() {
				hui.ui.showMessage({text:'Noten er gemt',icon:'common/success',duration:2000});
				this._refreshBase();
			}.bind(this)
		});
		noteFormula.reset();
		notePanel.hide();
	},
	$click$cancelNote : function() {
		noteFormula.reset();
		notePanel.hide();
	},

	//////////// Review //////////////

	$click$review : function() {
		reviewPanel.show();
		reviewList.setUrl('data/ListReviews.php?pageId='+this.pageId);
	},
	$click$reviewReject : function() {
		this._sendReview(false);
	},
	$click$reviewAccept : function() {
		this._sendReview(true);
	},
	_sendReview : function(accepted) {
		hui.ui.request({
			url : 'data/Review.php',
			parameters : {pageId : this.pageId, accepted : accepted},
			$success : function() {
				hui.ui.showMessage({text:'Revisionen er gemt!',icon:'common/success',duration:2000});
				reviewPanel.hide();
				this._updateState();
				this._refreshBase();
			}.bind(this)
		});
	},

  ////////////////// New page /////////////////

  $click$newPage : function() {
    newPagePanel.show();
    newPageFormula.focus();
  },
  $click$cancelNewPage : function() {
    newPagePanel.hide();
  },
  $submit$newPageFormula : function(form) {
    var values = form.getValues();
    if (hui.isBlank(values.title)) {
      newPageFormula.focus();
      return;
    }
    hui.ui.request({
      url : 'data/CreatePage.php',
      parameters : {
        pageId : this.pageId,
        title : values.title,
        placement : values.placement
      },
      $object : function(response) {
        document.location = 'index.php?id=' + response.id;
      }
    });
  }
};

hui.ui.listen(controller);

hui.ui.listen({
  $editPart : function(event) {
    hui.log('edit part',event)
    hui.ui.get('partWindow').show();
  },
  $cancelPart : function(event) {
    hui.ui.get('partWindow').hide();
  }
})