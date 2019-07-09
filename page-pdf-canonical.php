<?php
/**
 * Template Name: PDF Canonical
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Canonical_PDF_Fix
 */

get_header();
$file = get_query_var( 'file' );
?>
<div class="section">
	<main class="main-primary">
		<a href="<?php echo $file; ?>"><?php echo $file; ?></a>
	</main>
</div>
<?php

get_footer();
