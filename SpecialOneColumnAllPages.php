
<?php
if ( !defined( 'MEDIAWIKI' ) ) {
   die( 'This file is a MediaWiki extension. It is not a valid entry point' );
}

class SpecialOneColumnAllPages extends SpecialPage {
   function __construct( ) {
      parent::__construct( 'OneColumnAllPages' );
   }

   function execute( $par ) {
      $this->setHeaders();
      $viewOutput = $this->getOutput();
      $dbr = wfGetDB( DB_SLAVE );
      global $wgSitename;
      $output = "<big>'''" . wfMessage( 'onecolumnallpages-intro', $wgSitename )->plain()
         . "'''</big><br>";
      $namespaces = MWNamespace::getCanonicalNamespaces();
      if ( $par != 'page_id' ) {
         $res = $dbr->select( 'page', array ( 'page_title', 'page_namespace' ) );
      } else {
         $res = $dbr->select( 'page', array ( 'page_title', 'page_namespace' ),
            array( '1=1' ), __METHOD__, array( 'ORDER BY' => 'page_id ASC' ) );
      }
      foreach ( $res as $row ) {
         $output .= "[[:";
         if ( $row->page_namespace == 828 ) {
            $output .= 'Module:';
         } else {
            $output .= $namespaces[$row->page_namespace];
            if ( $namespaces[$row->page_namespace] ) {
               $output .= ':';
            }
         }
         $output .= $row->page_title . "]]<br>";
      }
      $viewOutput->addWikiText( $output );
      return $output;
   }
}
