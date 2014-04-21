<?php
if (!isset($GLOBALS['basePath'])) {
   header('HTTP/1.1 403 Forbidden');
	exit;
}

$HUMANISE_EDITOR_CLASSES = array (
  'all' => 
  array (
    'Console' => 'Utilities/Console.php',
    'DatabaseUtil' => 'Utilities/DatabaseUtil.php',
    'Dates' => 'Utilities/Dates.php',
    'DOMUtils' => 'Utilities/DOMUtils.php',
    'EventUtils' => 'Utilities/EventUtils.php',
    'GuiUtils' => 'Utilities/GuiUtils.php',
    'MarkupUtils' => 'Utilities/MarkupUtils.php',
    'SelectBuilder' => 'Utilities/SelectBuilder.php',
    'StopWatch' => 'Utilities/StopWatch.php',
    'StringBuilder' => 'Utilities/StringBuilder.php',
    'Strings' => 'Utilities/Strings.php',
    'TextDecorator' => 'Utilities/TextDecorator.php',
    'ValidateUtils' => 'Utilities/ValidateUtils.php',
    'AbstractObjectTest' => 'Tests/AbstractObjectTest.php',
    'AuthenticationTemplate' => 'Templates/AuthenticationTemplate.php',
    'AuthenticationTemplateController' => 'Templates/AuthenticationTemplateController.php',
    'CalendarTemplateController' => 'Templates/CalendarTemplateController.php',
    'DocumentTemplateController' => 'Templates/DocumentTemplateController.php',
    'DocumentTemplateEditor' => 'Templates/DocumentTemplateEditor.php',
    'GuestbookTemplateController' => 'Templates/GuestbookTemplateController.php',
    'HtmlTemplateController' => 'Templates/HtmlTemplateController.php',
    'SearchTemplate' => 'Templates/SearchTemplate.php',
    'SearchTemplateController' => 'Templates/SearchTemplateController.php',
    'SitemapTemplateController' => 'Templates/SitemapTemplateController.php',
    'TemplateController' => 'Templates/TemplateController.php',
    'WeblogTemplate' => 'Templates/WeblogTemplate.php',
    'WeblogTemplateController' => 'Templates/WeblogTemplateController.php',
    'AuthenticationService' => 'Services/AuthenticationService.php',
    'CacheService' => 'Services/CacheService.php',
    'ClassService' => 'Services/ClassService.php',
    'ClientService' => 'Services/ClientService.php',
    'ClipboardService' => 'Services/ClipboardService.php',
    'ConfigurationService' => 'Services/ConfigurationService.php',
    'DesignService' => 'Services/DesignService.php',
    'EventService' => 'Services/EventService.php',
    'FileService' => 'Services/FileService.php',
    'FileSystemService' => 'Services/FileSystemService.php',
    'FontService' => 'Services/FontService.php',
    'FrameService' => 'Services/FrameService.php',
    'HeartBeatService' => 'Services/HeartBeatService.php',
    'HierarchyService' => 'Services/HierarchyService.php',
    'ImageService' => 'Services/ImageService.php',
    'IssueService' => 'Services/IssueService.php',
    'JsonService' => 'Services/JsonService.php',
    'LogService' => 'Services/LogService.php',
    'MailService' => 'Services/MailService.php',
    'NewsService' => 'Services/NewsService.php',
    'ObjectLinkService' => 'Services/ObjectLinkService.php',
    'ObjectService' => 'Services/ObjectService.php',
    'OnlineObjectsService' => 'Services/OnlineObjectsService.php',
    'OptimizationService' => 'Services/OptimizationService.php',
    'PageService' => 'Services/PageService.php',
    'PartService' => 'Services/PartService.php',
    'PublishingService' => 'Services/PublishingService.php',
    'RelationsService' => 'Services/RelationsService.php',
    'RemoteDataService' => 'Services/RemoteDataService.php',
    'RenderingService' => 'Services/RenderingService.php',
    'ReportService' => 'Services/ReportService.php',
    'SchemaService' => 'Services/SchemaService.php',
    'SettingService' => 'Services/SettingService.php',
    'StatisticsService' => 'Services/StatisticsService.php',
    'TemplateService' => 'Services/TemplateService.php',
    'TestService' => 'Services/TestService.php',
    'ToolService' => 'Services/ToolService.php',
    'WaterusageService' => 'Services/WaterusageService.php',
    'XmlService' => 'Services/XmlService.php',
    'XslService' => 'Services/XslService.php',
    'ZipService' => 'Services/ZipService.php',
    'FilePart' => 'Parts/FilePart.php',
    'FilePartController' => 'Parts/FilePartController.php',
    'FormulaPart' => 'Parts/FormulaPart.php',
    'FormulaPartController' => 'Parts/FormulaPartController.php',
    'HeaderPart' => 'Parts/HeaderPart.php',
    'HeaderPartController' => 'Parts/HeaderPartController.php',
    'HorizontalrulePart' => 'Parts/HorizontalrulePart.php',
    'HorizontalrulePartController' => 'Parts/HorizontalrulePartController.php',
    'HtmlPart' => 'Parts/HtmlPart.php',
    'HtmlPartController' => 'Parts/HtmlPartController.php',
    'ImagegalleryPart' => 'Parts/ImagegalleryPart.php',
    'ImagegalleryPartController' => 'Parts/ImagegalleryPartController.php',
    'ImagePart' => 'Parts/ImagePart.php',
    'ImagePartController' => 'Parts/ImagePartController.php',
    'ListingPart' => 'Parts/ListingPart.php',
    'ListingPartController' => 'Parts/ListingPartController.php',
    'ListPart' => 'Parts/ListPart.php',
    'ListPartController' => 'Parts/ListPartController.php',
    'MailinglistPart' => 'Parts/MailinglistPart.php',
    'MailinglistPartController' => 'Parts/MailinglistPartController.php',
    'MapPart' => 'Parts/MapPart.php',
    'MapPartController' => 'Parts/MapPartController.php',
    'MoviePart' => 'Parts/MoviePart.php',
    'MoviePartController' => 'Parts/MoviePartController.php',
    'NewsPart' => 'Parts/NewsPart.php',
    'NewsPartController' => 'Parts/NewsPartController.php',
    'Part' => 'Parts/Part.php',
    'PartContext' => 'Parts/PartContext.php',
    'PartController' => 'Parts/PartController.php',
    'PersonPart' => 'Parts/PersonPart.php',
    'PersonPartController' => 'Parts/PersonPartController.php',
    'PosterPart' => 'Parts/PosterPart.php',
    'PosterPartController' => 'Parts/PosterPartController.php',
    'RichtextPart' => 'Parts/RichtextPart.php',
    'RichtextPartController' => 'Parts/RichtextPartController.php',
    'TablePart' => 'Parts/TablePart.php',
    'TablePartController' => 'Parts/TablePartController.php',
    'TextPart' => 'Parts/TextPart.php',
    'TextPartController' => 'Parts/TextPartController.php',
    'Address' => 'Objects/Address.php',
    'Cachedurl' => 'Objects/Cachedurl.php',
    'Calendar' => 'Objects/Calendar.php',
    'Calendarsource' => 'Objects/Calendarsource.php',
    'Design' => 'Objects/Design.php',
    'Emailaddress' => 'Objects/Emailaddress.php',
    'Event' => 'Objects/Event.php',
    'Feedback' => 'Objects/Feedback.php',
    'File' => 'Objects/File.php',
    'Filegroup' => 'Objects/Filegroup.php',
    'Image' => 'Objects/Image.php',
    'Imagegroup' => 'Objects/Imagegroup.php',
    'Issue' => 'Objects/Issue.php',
    'Issuestatus' => 'Objects/Issuestatus.php',
    'Mailinglist' => 'Objects/Mailinglist.php',
    'Milestone' => 'Objects/Milestone.php',
    'News' => 'Objects/News.php',
    'Newsgroup' => 'Objects/Newsgroup.php',
    'Newssource' => 'Objects/Newssource.php',
    'Newssourceitem' => 'Objects/Newssourceitem.php',
    'Pageblueprint' => 'Objects/Pageblueprint.php',
    'Path' => 'Objects/Path.php',
    'Person' => 'Objects/Person.php',
    'Persongroup' => 'Objects/Persongroup.php',
    'Personrole' => 'Objects/Personrole.php',
    'Phonenumber' => 'Objects/Phonenumber.php',
    'Problem' => 'Objects/Problem.php',
    'Product' => 'Objects/Product.php',
    'Productgroup' => 'Objects/Productgroup.php',
    'Productoffer' => 'Objects/Productoffer.php',
    'Producttype' => 'Objects/Producttype.php',
    'Project' => 'Objects/Project.php',
    'Remotepublisher' => 'Objects/Remotepublisher.php',
    'Review' => 'Objects/Review.php',
    'Securityzone' => 'Objects/Securityzone.php',
    'Task' => 'Objects/Task.php',
    'Testphrase' => 'Objects/Testphrase.php',
    'User' => 'Objects/User.php',
    'Watermeter' => 'Objects/Watermeter.php',
    'Waterusage' => 'Objects/Waterusage.php',
    'Weblogentry' => 'Objects/Weblogentry.php',
    'Webloggroup' => 'Objects/Webloggroup.php',
    'Feed' => 'Network/Feed.php',
    'FeedItem' => 'Network/FeedItem.php',
    'FeedParser' => 'Network/FeedParser.php',
    'FeedSerializer' => 'Network/FeedSerializer.php',
    'FileUpload' => 'Network/FileUpload.php',
    'HttpClient' => 'Network/HttpClient.php',
    'ImportResult' => 'Network/ImportResult.php',
    'RemoteData' => 'Network/RemoteData.php',
    'RemoteFile' => 'Network/RemoteFile.php',
    'UserAgentAnalyzer' => 'Network/UserAgentAnalyzer.php',
    'WebRequest' => 'Network/WebRequest.php',
    'WebResponse' => 'Network/WebResponse.php',
    'WatermeterSummary' => 'Modules/Water/WatermeterSummary.php',
    'ConsoleReporter' => 'Modules/Testing/ConsoleReporter.php',
    'StatisticsQuery' => 'Modules/Statistics/StatisticsQuery.php',
    'ReviewCombo' => 'Modules/Review/ReviewCombo.php',
    'ReviewService' => 'Modules/Review/ReviewService.php',
    'NewsArticle' => 'Modules/News/NewsArticle.php',
    'LinkInfo' => 'Modules/Links/LinkInfo.php',
    'LinkQuery' => 'Modules/Links/LinkQuery.php',
    'LinkService' => 'Modules/Links/LinkService.php',
    'LinkView' => 'Modules/Links/LinkView.php',
    'Inspection' => 'Modules/Inspection/Inspection.php',
    'InspectionService' => 'Modules/Inspection/InspectionService.php',
    'Gradient' => 'Modules/Images/Gradient.php',
    'ImageTransformationRecipe' => 'Modules/Images/ImageTransformationRecipe.php',
    'ImageTransformationService' => 'Modules/Images/ImageTransformationService.php',
    'Graph' => 'Modules/Graphs/Graph.php',
    'GraphNode' => 'Modules/Graphs/GraphNode.php',
    'Graphviz' => 'Modules/Graphs/Graphviz.php',
    'Entity' => 'Model/Entity.php',
    'Frame' => 'Model/Frame.php',
    'Hierarchy' => 'Model/Hierarchy.php',
    'HierarchyItem' => 'Model/HierarchyItem.php',
    'Link' => 'Model/Link.php',
    'Object' => 'Model/Object.php',
    'ObjectLink' => 'Model/ObjectLink.php',
    'Page' => 'Model/Page.php',
    'PartLink' => 'Model/PartLink.php',
    'SpecialPage' => 'Model/SpecialPage.php',
    'Template' => 'Model/Template.php',
    'DiagramData' => 'Interface/DiagramData.php',
    'DiagramEdge' => 'Interface/DiagramEdge.php',
    'DiagramNode' => 'Interface/DiagramNode.php',
    'In2iGui' => 'Interface/In2iGui.php',
    'ItemsWriter' => 'Interface/ItemsWriter.php',
    'ListWriter' => 'Interface/ListWriter.php',
    'UI' => 'Interface/UI.php',
    'GoogleAnalytics' => 'Integration/GoogleAnalytics.php',
    'CSVWriter' => 'Formats/CSVWriter.php',
    'DBUCalendar' => 'Formats/DBUCalendar.php',
    'DBUCalendarEvent' => 'Formats/DBUCalendarEvent.php',
    'DBUCalendarParser' => 'Formats/DBUCalendarParser.php',
    'HtmlDocument' => 'Formats/HtmlDocument.php',
    'HtmlTableParser' => 'Formats/HtmlTableParser.php',
    'VCalendar' => 'Formats/VCalendar.php',
    'VCalParser' => 'Formats/VCalParser.php',
    'VCalSerializer' => 'Formats/VCalSerializer.php',
    'VEvent' => 'Formats/VEvent.php',
    'VRecurrenceRule' => 'Formats/VRecurrenceRule.php',
    'ZipFile' => 'Formats/ZipFile.php',
    'ZipFileItem' => 'Formats/ZipFileItem.php',
    'ClassInfo' => 'Core/ClassInfo.php',
    'ClassPropertyInfo' => 'Core/ClassPropertyInfo.php',
    'ClassRelationInfo' => 'Core/ClassRelationInfo.php',
    'Database' => 'Core/Database.php',
    'ExternalSession' => 'Core/ExternalSession.php',
    'InternalSession' => 'Core/InternalSession.php',
    'Loadable' => 'Core/Loadable.php',
    'Log' => 'Core/Log.php',
    'PageQuery' => 'Core/PageQuery.php',
    'Query' => 'Core/Query.php',
    'Request' => 'Core/Request.php',
    'Response' => 'Core/Response.php',
    'SearchResult' => 'Core/SearchResult.php',
    'SystemInfo' => 'Core/SystemInfo.php',
    'TemporaryFolder' => 'Core/TemporaryFolder.php',
  ),
  'interfaces' => 
  array (
    'Loadable' => 
    array (
      0 => 'Frame',
      1 => 'Hierarchy',
      2 => 'Link',
    ),
  ),
  'parents' => 
  array (
    'UnitTestCase' => 
    array (
      0 => 'AbstractObjectTest',
    ),
    'SimpleTestCase' => 
    array (
      0 => 'AbstractObjectTest',
    ),
    'TemplateController' => 
    array (
      0 => 'AuthenticationTemplateController',
      1 => 'CalendarTemplateController',
      2 => 'DocumentTemplateController',
      3 => 'GuestbookTemplateController',
      4 => 'HtmlTemplateController',
      5 => 'SearchTemplateController',
      6 => 'SitemapTemplateController',
      7 => 'WeblogTemplateController',
    ),
    'Part' => 
    array (
      0 => 'FilePart',
      1 => 'FormulaPart',
      2 => 'HeaderPart',
      3 => 'HorizontalrulePart',
      4 => 'HtmlPart',
      5 => 'ImagegalleryPart',
      6 => 'ImagePart',
      7 => 'ListingPart',
      8 => 'ListPart',
      9 => 'MailinglistPart',
      10 => 'MapPart',
      11 => 'MoviePart',
      12 => 'NewsPart',
      13 => 'PersonPart',
      14 => 'PosterPart',
      15 => 'RichtextPart',
      16 => 'TablePart',
      17 => 'TextPart',
    ),
    'Entity' => 
    array (
      0 => 'FilePart',
      1 => 'FormulaPart',
      2 => 'HeaderPart',
      3 => 'HorizontalrulePart',
      4 => 'HtmlPart',
      5 => 'ImagegalleryPart',
      6 => 'ImagePart',
      7 => 'ListingPart',
      8 => 'ListPart',
      9 => 'MailinglistPart',
      10 => 'MapPart',
      11 => 'MoviePart',
      12 => 'NewsPart',
      13 => 'Part',
      14 => 'PersonPart',
      15 => 'PosterPart',
      16 => 'RichtextPart',
      17 => 'TablePart',
      18 => 'TextPart',
      19 => 'Address',
      20 => 'Cachedurl',
      21 => 'Calendar',
      22 => 'Calendarsource',
      23 => 'Design',
      24 => 'Emailaddress',
      25 => 'Event',
      26 => 'Feedback',
      27 => 'File',
      28 => 'Filegroup',
      29 => 'Image',
      30 => 'Imagegroup',
      31 => 'Issue',
      32 => 'Issuestatus',
      33 => 'Mailinglist',
      34 => 'Milestone',
      35 => 'News',
      36 => 'Newsgroup',
      37 => 'Newssource',
      38 => 'Newssourceitem',
      39 => 'Pageblueprint',
      40 => 'Path',
      41 => 'Person',
      42 => 'Persongroup',
      43 => 'Personrole',
      44 => 'Phonenumber',
      45 => 'Problem',
      46 => 'Product',
      47 => 'Productgroup',
      48 => 'Productoffer',
      49 => 'Producttype',
      50 => 'Project',
      51 => 'Remotepublisher',
      52 => 'Review',
      53 => 'Securityzone',
      54 => 'Task',
      55 => 'Testphrase',
      56 => 'User',
      57 => 'Watermeter',
      58 => 'Waterusage',
      59 => 'Weblogentry',
      60 => 'Webloggroup',
      61 => 'Frame',
      62 => 'Hierarchy',
      63 => 'HierarchyItem',
      64 => 'Link',
      65 => 'Object',
      66 => 'ObjectLink',
      67 => 'Page',
      68 => 'PartLink',
      69 => 'SpecialPage',
      70 => 'Template',
    ),
    'PartController' => 
    array (
      0 => 'FilePartController',
      1 => 'FormulaPartController',
      2 => 'HeaderPartController',
      3 => 'HorizontalrulePartController',
      4 => 'HtmlPartController',
      5 => 'ImagegalleryPartController',
      6 => 'ImagePartController',
      7 => 'ListingPartController',
      8 => 'ListPartController',
      9 => 'MailinglistPartController',
      10 => 'MapPartController',
      11 => 'MoviePartController',
      12 => 'NewsPartController',
      13 => 'PersonPartController',
      14 => 'PosterPartController',
      15 => 'RichtextPartController',
      16 => 'TablePartController',
      17 => 'TextPartController',
    ),
    'Object' => 
    array (
      0 => 'Address',
      1 => 'Cachedurl',
      2 => 'Calendar',
      3 => 'Calendarsource',
      4 => 'Design',
      5 => 'Emailaddress',
      6 => 'Event',
      7 => 'Feedback',
      8 => 'File',
      9 => 'Filegroup',
      10 => 'Image',
      11 => 'Imagegroup',
      12 => 'Issue',
      13 => 'Issuestatus',
      14 => 'Mailinglist',
      15 => 'Milestone',
      16 => 'News',
      17 => 'Newsgroup',
      18 => 'Newssource',
      19 => 'Newssourceitem',
      20 => 'Pageblueprint',
      21 => 'Path',
      22 => 'Person',
      23 => 'Persongroup',
      24 => 'Personrole',
      25 => 'Phonenumber',
      26 => 'Problem',
      27 => 'Product',
      28 => 'Productgroup',
      29 => 'Productoffer',
      30 => 'Producttype',
      31 => 'Project',
      32 => 'Remotepublisher',
      33 => 'Review',
      34 => 'Securityzone',
      35 => 'Task',
      36 => 'Testphrase',
      37 => 'User',
      38 => 'Watermeter',
      39 => 'Waterusage',
      40 => 'Weblogentry',
      41 => 'Webloggroup',
    ),
    'SimpleReporter' => 
    array (
      0 => 'ConsoleReporter',
    ),
    'SimpleScorer' => 
    array (
      0 => 'ConsoleReporter',
    ),
  ),
)
?>