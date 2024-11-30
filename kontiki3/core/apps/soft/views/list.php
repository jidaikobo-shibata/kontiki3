<h1><?php echo $pagetitle ?></h1>
<?php
$html = '';
if (empty($items)):
	$html.= '<p>no items</p>';
else:
	$html.= '<ul>';
	foreach ($items as $item):
		$html.= '<li>';
		$html.= '<a href="'.homeUrl().'/information/'.escUrl($item['slug']).'">';
		$html.= escHtml($item['title']);
		$html.= '</a>';
		$html.= '</li>';
	endforeach;
	$html.= '</ul>';
endif;
echo $html;
