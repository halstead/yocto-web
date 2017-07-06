<?php 
 use Roots\Sage\BlockBuilder;
?>

<?php 
$attsArray = array(
	"custom-post-type" => "page",
	"featured-image" => "true",
	"content" => "true",
	"block-class" => "col-xs-12 section-block"
);
echo BlockBuilder\custom_project_blocks_function($attsArray);

?>