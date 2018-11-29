hui.ui.listen({

  userShowedUpload : false,

  $filesDropped$gallery : function(files) {
    this._filesDropped(files);
  },
  $filesDropped$list : function(files) {
    this._filesDropped(files);
  },
  _filesDropped : function(files) {
    uploadWindow.show();
    file.uploadFiles(files);
  },

  $urlDropped$gallery : function(url) {
    this._urlDroppped(url);
  },
  $urlDropped$list : function(url) {
    this._urlDroppped(url);
  },
  _urlDroppped : function(url) {
    hui.log(url);
    if (hui.string.startsWith(url,'data:')) {
      hui.ui.msg.fail({text:{en:'This type is not supported',da:'Denne type er desværre endnu ikke understøttet'}});
    } else {
      this._fetch(url);
    }
  },

  ///////////////////////// Uoload /////////////////////////

  $click$newFile : function() {
    uploadWindow.show();
    this.userShowedUpload = true;
  },
  $click$cancelUpload : function() {
    uploadWindow.hide();
  },
  $uploadDidComplete$file : function() {
    imagesSource.refresh();
    listSource.refresh();
    groupSource.refresh();
    subsetSource.refresh();
  },
  $uploadDidCompleteQueue$file : function() {
    if (!this.userShowedUpload) {
      //uploadWindow.hide();
    }
  },
  $userClosedWindow$uploadWindow : function() {
    this.userShowedUpload = false;
  },

  ////////////////////////// Fetch /////////////////////////

  $click$fetchImage : function() {
    var values = fetchFormula.getValues();
    this._fetch(values.url);
  },
  _fetch : function(url) {
    fetchImage.setEnabled(false);
    hui.ui.msg({text:{en:'Fetching image...', da:'Henter billede...'},busy:true});
    hui.ui.request({
      url : 'actions/FetchImage.php',
      $success : 'imageFetched',
      parameters : {url : url}
    });
  },
  $click$cancelFetch : function() {
    uploadWindow.hide();
  },
  $success$imageFetched : function(data) {
    if (data.success) {
      fetchFormula.reset();
      hui.ui.msg.success({text:{en:'The image has been fetched', da:'Billedet er hentet'}});
      listSource.refresh();
      imagesSource.refresh();
      subsetSource.refresh();
      groupSource.refresh();
    } else {
      hui.log(data);
      hui.ui.msg.fail({text:data.message});
    }
    fetchImage.setEnabled(true);
  }
})
