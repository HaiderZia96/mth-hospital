/**
 * @license Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    config.allowedContent = true;
    config.embed_provider = '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}';
    config.filebrowserBrowseUrl= '../assets/ckeditor/ckfinder/ckfinder.html';
    //xml,ajax,cloudservices,button,balloonpanel,balloontoolbar,filetools,imagebase,easyimage
    config.extraPlugins = 'attach,collapsibleItem,accordionList,imgbrowse,youtube,embed,embedbase,dialog,widget,notificationaggregator,dialogui,clipboard,lineutils,widgetselection,notification';
};
