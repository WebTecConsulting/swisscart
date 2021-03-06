<?php
/* $Id$
osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com
Copyright (c) 2002 osCommerce

Released under the GNU General Public License
xsell.php
Original Idea From Isaac Mualem im@imwebdesigning.com <mailto:im@imwebdesigning.com>
Complete Recoding From Stephen Walker admin@snjcomputers.com
*/

  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
   $currencies = new currencies();
  define('CURRENT_PAGE', basename($PHP_SELF));

  switch($_GET['action']){
	  case 'update_cross' :
		if ($_POST['product']){
	    foreach ($_POST['product'] as $temp_prod){
          tep_db_query('delete from ' . TABLE_PRODUCTS_XSELL . ' where xsell_id = "'.$temp_prod.'" and products_id = "'.$_GET['add_related_product_ID'].'"');
	    }
	  }

		$sort_start_query = tep_db_query('select sort_order from ' . TABLE_PRODUCTS_XSELL . ' where products_id = "'.$_GET['add_related_product_ID'].'" order by sort_order desc limit 1');
        $sort_start = tep_db_fetch_array($sort_start_query);

	    $sort = (($sort_start['sort_order'] > 0) ? $sort_start['sort_order'] : '0');
		if ($_POST['cross']){
        foreach ($_POST['cross'] as $temp){
			$sort++;
			$insert_array = array();
			$insert_array = array('products_id' => $_GET['add_related_product_ID'],
				                  'xsell_id' => $temp,
				                  'sort_order' => $sort);
              tep_db_perform(TABLE_PRODUCTS_XSELL, $insert_array);
		}
		}
        $messageStack->add(CROSS_SELL_SUCCESS, 'success');
	   break;
	  case 'update_sort' :
        foreach ($_POST as $key_a => $value_a){
         tep_db_query('update ' . TABLE_PRODUCTS_XSELL . ' set sort_order = "' . $value_a . '" where xsell_id = "' . $key_a . '"');
	    }
        $messageStack->add(SORT_CROSS_SELL_SUCCESS, 'success');
	   break;
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<style>
.productmenutitle{
cursor:pointer;
margin-bottom: 0px;
background-color:orange;
color:#FFFFFF;
font-weight:bold;
font-family:ms sans serif;
width:100%;
padding:3px;
font-size:12px;
text-align:center;
/*/*/border:1px solid #000000;/* */
}
.productmenutitle1{
cursor:pointer;
margin-bottom: 0px;
background-color: red;
color:#FFFFFF;
font-weight:bold;
font-family:ms sans serif;
width:100%;
padding:3px;
font-size:12px;
text-align:center;
/*/*/border:1px solid #000000;/* */
}
</style>
<script language="JavaScript1.2">

function cOn(td)
{
if(document.getElementById||(document.all && !(document.getElementById)))
{
td.style.backgroundColor="#CCCCCC";
}
}

function cOnA(td)
{
if(document.getElementById||(document.all && !(document.getElementById)))
{
td.style.backgroundColor="#CCFFFF";
}
}

function cOut(td)
{
if(document.getElementById||(document.all && !(document.getElementById)))
{
td.style.backgroundColor="DFE4F4";
}
}
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
 <tr>
  <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
  </table></td>
  <td width="100%" valign="top">
<!-- body_text //-->
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
   <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10');?></td>
   </tr>
   <tr>
    <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
   </tr>
   <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '15');?></td>
   </tr>
  </table>

<?php
  if ($_GET['add_related_product_ID'] == ''){
?>
  <table border="0" cellpadding="0" cellspacing="0">
       <tr>
         <td align="left" colspan="2" class="main searchfields"><?php
          // search filter begin
            $search_terms = $_REQUEST['search_terms'];
            $search_params = $pr_name = $pr_model = '';
            $temp = preg_split ('/[\s,]/', trim($search_terms));
            if (count($temp)>0){
              foreach ($temp as $word){
                if (tep_not_null($word)){
                  $pr_name .= 'pd.products_name like "%'.$word.'%" and ';
                  $pr_model .= 'p.products_model like "%'.$word.'%" and ';
                }
              }
            }
            if (tep_not_null($pr_name)){
              $search_params .= ' and ('.substr($pr_name, 0, strlen($pr_name)-4).') or ('.substr($pr_model, 0, strlen($pr_model)-4).')';
            }
           echo tep_draw_form('search_cross', FILENAME_XSELL_PRODUCTS, '', 'get');
           foreach($_GET as $name => $value){
             if(!in_array($name, array('action', 'search_terms','x', 'y') )){
               echo tep_draw_hidden_field($name, $value)."\n";
             }
           }
           echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">'.
                  '<tr>'.
                    '<td class="main" style="text-align: right; vertical-align: middle;">'. TEXT_SEARCH_FILTER. tep_draw_input_field('search_terms', '', 'size="65"'). '</td>'.
                    '<td class="main" style="padding-left: 5px;">'. tep_image_submit('button_search.gif', IMAGE_SEARCH) . '</td>'.
                '</table> ';
           echo '</form>';
           // search filter end
         ?></td>
       </tr>
  </table>
<?php
  // process sort order:
  $listing = $_GET['listing'];
  switch($listing){
    case 'id':
      $order = 'p.products_id';
      break;
    case 'productname':
      $order = 'pd.products_name';
      break;
    case 'model':
    default:
      $listing = 'model';
      $order = 'p.products_model asc';
  }
?>
  <table border="0" cellspacing="1" cellpadding="2" align="left">
   <tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent" width="75" style="white-space: nowrap; padding: 0 5px 0 5px;"><?php
      echo ($listing=='id' ? tep_image(DIR_WS_IMAGES.'icons/arrow_down.gif', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px;"').TABLE_HEADING_PRODUCT_ID
                           : tep_image(DIR_WS_IMAGES.'icons/arrow_down_grey.png', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px; white-space: nowrap;"').'<a href="'.tep_href_link(CURRENT_PAGE, tep_get_all_get_params(array('listing')).'listing=id').'">'.  TABLE_HEADING_PRODUCT_ID . '</a>');
    ?></td>
    <td class="dataTableHeadingContent" style="white-space: nowrap; padding: 0 5px 0 5px;"><?php
      echo ($listing=='model'?tep_image(DIR_WS_IMAGES.'icons/arrow_down.gif', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px;"').TABLE_HEADING_PRODUCT_MODEL
                             :tep_image(DIR_WS_IMAGES.'icons/arrow_down_grey.png', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px; white-space: nowrap;"').'<a href="'.tep_href_link(CURRENT_PAGE, tep_get_all_get_params(array('listing')).'listing=model').'">'. TABLE_HEADING_PRODUCT_MODEL . '</a>');
    ?></td>
    <td class="dataTableHeadingContent">&nbsp;<?php
      echo ($listing=='productname'?tep_image(DIR_WS_IMAGES.'icons/arrow_down.gif', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px;"') . TABLE_HEADING_PRODUCT_NAME
                                   :tep_image(DIR_WS_IMAGES.'icons/arrow_down_grey.png', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px; white-space: nowrap;"').'<a href="'.tep_href_link(CURRENT_PAGE, tep_get_all_get_params(array('listing')).'listing=productname').'">'. TABLE_HEADING_PRODUCT_NAME . '</a>') ;
    ?></td>
    <td class="dataTableHeadingContent" nowrap><?php echo TABLE_HEADING_CURRENT_SELLS;?></td>
    <td class="dataTableHeadingContent" colspan="2" nowrap align="center"><?php echo TABLE_HEADING_UPDATE_SELLS;?></td>
   </tr>
<?php
    $products_query_raw = 'select p.products_id, p.products_model, pd.products_name, p.products_id '.
                          ' from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd '.
                          ' where p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" '.$search_params.
                          ' order by '. $order;
    $products_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
    $products_query = tep_db_query($products_query_raw);
    while ($products = tep_db_fetch_array($products_query)) {
?>
   <tr onMouseOver="cOn(this); this.style.cursor='pointer'; this.style.cursor='hand';" onMouseOut="cOut(this);" bgcolor='#DFE4F4' onClick=document.location.href="<?php echo tep_href_link(FILENAME_XSELL_PRODUCTS, 'add_related_product_ID=' . $products['products_id'], 'NONSSL');?>">
    <td class="dataTableContent" valign="top">&nbsp;<?php echo $products['products_id'];?>&nbsp;</td>
    <td class="dataTableContent" valign="top">&nbsp;<?php echo $products['products_model'];?>&nbsp;</td>
    <td class="dataTableContent" valign="top">&nbsp;<?php echo $products['products_name'];?>&nbsp;</td>
    <td class="dataTableContent" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
    $products_cross_query = tep_db_query('select p.products_id, p.products_model, pd.products_name, p.products_id, x.products_id, x.xsell_id, x.sort_order, x.ID from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd, '.TABLE_PRODUCTS_XSELL.' x where x.xsell_id = p.products_id and x.products_id = "'.$products['products_id'].'" and p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" order by x.sort_order asc');
	$i=0;
    while ($products_cross = tep_db_fetch_array($products_cross_query)){
		$i++;
?>
	 <tr>
	  <td class="dataTableContent">&nbsp;<?php echo $i . '.&nbsp;&nbsp;<b>' . $products_cross['products_model'] . '</b>&nbsp;' . $products_cross['products_name'];?>&nbsp;</td>
	 </tr>
<?php
	}
    if ($i <= 0){
?>
	 <tr>
	  <td class="dataTableContent">&nbsp;--&nbsp;</td>
	 </tr>
<?php
	}else{
?>
	 <tr>
	  <td class="dataTableContent"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10');?></td>
	 </tr>
<?php
}
?>
    </table></td>
    <td class="dataTableContent" valign="top">&nbsp;<a href="<?php echo tep_href_link(FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'add_related_product_ID=' . $products['products_id'], 'NONSSL');?>"><?php echo TEXT_EDIT_SELLS;?></a>&nbsp;</td>
    <td class="dataTableContent" valign="top" align="center">&nbsp;<?php echo (($i > 0) ? '<a href="' . tep_href_link(FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'sort=1&add_related_product_ID=' . $products['products_id'], 'NONSSL') .'">'.TEXT_SORT.'</a>&nbsp;' : '--')?></td>
   </tr>
<?php
	}
?>
   <tr>
    <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBoxContent">
     <tr>
      <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
      <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID', 'action'))); ?></td>
     </tr>
    </table></td>
   </tr>
  </table>
<?php
}elseif($_GET['add_related_product_ID'] != '' && $_GET['sort'] == ''){
	$products_name_query = tep_db_query('select pd.products_name, p.products_model, p.products_image from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = "'.$_GET['add_related_product_ID'].'" and p.products_id = pd.products_id and pd.language_id ="'.(int)$languages_id.'"');
	$products_name = tep_db_fetch_array($products_name_query);
?>
  <table border="0" cellspacing="0" cellpadding="0" align="left">
   <tr>
    <td>
      <table cellpadding="3" cellspacing="0" border="0" width="100%">
       <tr>
        <td valign="top" align="left" colspan="2"><span class="main"><?php echo TEXT_SETTING_SELLS.': <strong>'.$products_name['products_name'].'</strong> ('.TEXT_MODEL.': '.$products_name['products_model'].') ('.TEXT_PRODUCT_ID.': '.$_GET['add_related_product_ID'].')';?>  </span></td>
       </tr>
       <tr>
        <td align="right" colspan="2"><?php echo tep_image('../images/'.$products_name['products_image'], $products_name['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);?></td>
       </tr>
       <tr>
         <td align="left" colspan="2" class="main searchfields"><?php
          // search filter begin
            $search_terms = $_REQUEST['search_terms'];
            $search_params = $pr_name = $pr_model = '';
            $temp = preg_split ('/[\s,]/', trim($search_terms));
            if (count($temp)>0){
              foreach ($temp as $word){
                if (tep_not_null($word)){
                  $pr_name .= 'pd.products_name like "%'.$word.'%" and ';
                  $pr_model .= 'p.products_model like "%'.$word.'%" and ';
                }
              }
            }
            if (tep_not_null($pr_name)){
              $search_params .= ' and ('.substr($pr_name, 0, strlen($pr_name)-4).') or ('.substr($pr_model, 0, strlen($pr_model)-4).')';
            }
           echo tep_draw_form('search_cross', FILENAME_XSELL_PRODUCTS, '', 'get');
           foreach($_GET as $name => $value){
             if(!in_array($name, array('action', 'search_terms','x', 'y') )){
               echo tep_draw_hidden_field($name, $value)."\n";
             }
           }
           echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">'.
                  '<tr>'.
                    '<td class="main" style="text-align: right; vertical-align: middle;">'. TEXT_SEARCH_FILTER. tep_draw_input_field('search_terms', '', 'size="65"'). '</td>'.
                    '<td class="main" style="padding-left: 5px;">'. tep_image_submit('button_search.gif', IMAGE_SEARCH) . '</td>'.
                '</table> ';
           echo '</form>';
           // search filter end
         ?></td>
       </tr>
      </table>
<?php echo tep_draw_form('update_cross', FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'action=update_cross', 'post');
  // process sort order:
  $listing = $_GET['listing'];
  switch($listing){
    case 'id':
      $order = 'p.products_id';
      break;
    case 'productname':
      $order = 'pd.products_name';
      break;
    case 'model':
    default:
      $listing = 'model';
      $order = 'p.products_model asc';
  }
?>
      <table cellpadding="1" cellspacing="1" border="0">
     <tr class="dataTableHeadingRow">
      <td class="dataTableHeadingContent" width="75" style="white-space: nowrap; padding: 0 5px 0 5px;"><?php
        echo ($listing=='id' ? tep_image(DIR_WS_IMAGES.'icons/arrow_down.gif', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px;"').TABLE_HEADING_PRODUCT_ID
                             : tep_image(DIR_WS_IMAGES.'icons/arrow_down_grey.png', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px; white-space: nowrap;"').'<a href="'.tep_href_link(CURRENT_PAGE, tep_get_all_get_params(array('listing')).'listing=id').'">'.  TABLE_HEADING_PRODUCT_ID . '</a>');
      ?></td>
      <td class="dataTableHeadingContent" style="white-space: nowrap; padding: 0 5px 0 5px;"><?php
        echo ($listing=='model'?tep_image(DIR_WS_IMAGES.'icons/arrow_down.gif', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px;"').TABLE_HEADING_PRODUCT_MODEL
                               :tep_image(DIR_WS_IMAGES.'icons/arrow_down_grey.png', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px; white-space: nowrap;"').'<a href="'.tep_href_link(CURRENT_PAGE, tep_get_all_get_params(array('listing')).'listing=model').'">'. TABLE_HEADING_PRODUCT_MODEL . '</a>');
      ?></td>
	    <td class="dataTableHeadingContent" style="white-space: nowrap; padding: 0 5px 0 5px;"><?php echo TABLE_HEADING_PRODUCT_IMAGE;?></td>
	    <td class="dataTableHeadingContent" style="white-space: nowrap; padding: 0 5px 0 5px;"><?php echo TABLE_HEADING_CROSS_SELL_THIS;?></td>
      <td class="dataTableHeadingContent">&nbsp;<?php
        echo ($listing=='productname'?tep_image(DIR_WS_IMAGES.'icons/arrow_down.gif', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px;"') . TABLE_HEADING_PRODUCT_NAME
                                     :tep_image(DIR_WS_IMAGES.'icons/arrow_down_grey.png', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px; white-space: nowrap;"').'<a href="'.tep_href_link(CURRENT_PAGE, tep_get_all_get_params(array('listing')).'listing=productname').'">'. TABLE_HEADING_PRODUCT_NAME . '</a>') ;
      ?></td>
	  <td class="dataTableHeadingContent" style="white-space: nowrap; padding: 0 5px 0 5px;"><?php echo TABLE_HEADING_PRODUCT_PRICE;?>&nbsp;</td>
	 </tr>
<?php
    $products_query_raw = 'select p.products_id, p.products_model, p.products_image, p.products_price, pd.products_name, p.products_id '.
                          ' from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd '.
                          ' where p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" '.
                                  $search_params.
                          ' order by '.$order;
    $products_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
    $products_query = tep_db_query($products_query_raw);
    while ($products = tep_db_fetch_array($products_query)) {
		$xsold_query = tep_db_query('select * from '.TABLE_PRODUCTS_XSELL.' where products_id = "'.$_GET['add_related_product_ID'].'" and xsell_id = "'.$products['products_id'].'"');
?>
	 <tr bgcolor='#DFE4F4'>
	  <td class="dataTableContent" align="center">&nbsp;<?php echo $products['products_id'];?>&nbsp;</td>
	  <td class="dataTableContent" align="center">&nbsp;<?php echo $products['products_model'];?>&nbsp;</td>
	  <td class="dataTableContent" align="center">&nbsp;<?php echo ((is_file('../images/'.$products['products_image'])) ?  tep_image('../images/'.$products['products_image'], $products_name['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) : '<br>No Image<br>');?>&nbsp;</td>
	  <td class="dataTableContent">&nbsp;<?php echo tep_draw_hidden_field('product[]', $products['products_id']) . tep_draw_checkbox_field('cross[]', $products['products_id'], ((tep_db_num_rows($xsold_query) > 0) ? true : false), '', ' onMouseOver="this.style.cursor=\'hand\'"');?>&nbsp;<label onMouseOver="this.style.cursor='hand'"><?php echo TEXT_CROSS_SELL;?></label>&nbsp;</td>
	  <td class="dataTableContent">&nbsp;<?php echo $products['products_name'];?>&nbsp;</td>
	  <td class="dataTableContent">&nbsp;<?php echo $currencies->format($products['products_price']);?>&nbsp;</td>
	 </tr>
<?php
    }
?>
     <tr bgcolor='#DFE4F4'>
      <td class="dataTableContent" align="right" valign="bottom" colspan="6"><?php
        echo tep_image_submit('button_update.gif') .
             '&nbsp;<a href="'.tep_href_link(FILENAME_XSELL_PRODUCTS, 'men_id=catalog').'">' . tep_image_button('button_cancel.gif') . '</a>';
      ?></td>
     </tr>
	</table></form></td>
   </tr>
   <tr>
    <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBoxContent">
     <tr>
      <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
      <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID', 'action'))); ?></td>
     </tr>
    </table></td>
   </tr>
  </table>
<?php
} elseif( $_GET['add_related_product_ID'] != '' && $_GET['sort'] != ''){
	$products_name_query = tep_db_query('select pd.products_name, p.products_model, p.products_image from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = "'.$_GET['add_related_product_ID'].'" and p.products_id = pd.products_id and pd.language_id ="'.(int)$languages_id.'"');
	$products_name = tep_db_fetch_array($products_name_query);
?>
  <table cellpadding="3" cellspacing="0" border="0" width="100%">
    <tr>
      <td valign="top"><span class="main"><?php echo TEXT_SORT_ORDER . ': <strong>' . $products_name['products_name'].'</strong> (Model: '.$products_name['products_model'].') (Product ID: '.$_GET['add_related_product_ID'].')';?></span></td>
    </tr>
    <tr>
      <td align="right"><?php echo tep_image('../images/'.$products_name['products_image']);?></td>
    </tr>
       <tr>
         <td align="left" class="main searchfields"><?php
          // search filter begin
            $search_terms = $_REQUEST['search_terms'];
            $search_params = $pr_name = $pr_model = '';
            $temp = preg_split ('/[\s,]/', trim($search_terms));
            if (count($temp)>0){
              foreach ($temp as $word){
                if (tep_not_null($word)){
                  $pr_name .= 'pd.products_name like "%'.$word.'%" and ';
                  $pr_model .= 'p.products_model like "%'.$word.'%" and ';
                }
              }
            }
            if (tep_not_null($pr_name)){
              $search_params .= ' and ('.substr($pr_name, 0, strlen($pr_name)-4).') or ('.substr($pr_model, 0, strlen($pr_model)-4).')';
            }
           echo tep_draw_form('search_cross', FILENAME_XSELL_PRODUCTS, '', 'get');
           foreach($_GET as $name => $value){
             if(!in_array($name, array('action', 'search_terms','x', 'y') )){
               echo tep_draw_hidden_field($name, $value)."\n";
             }
           }
           echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">'.
                  '<tr>'.
                    '<td class="main" style="text-align: right; vertical-align: middle;">'. TEXT_SEARCH_FILTER. tep_draw_input_field('search_terms', '', 'size="65"'). '</td>'.
                    '<td class="main" style="padding-left: 5px;">'. tep_image_submit('button_search.gif', IMAGE_SEARCH) . '</td>'.
                '</table> ';
           echo '</form>';
           // search filter end
         ?></td>
       </tr>
  </table>
  <?php echo tep_draw_form('update_sort', FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'action=update_sort', 'post');
  // process sort order:
  $listing = $_GET['listing'];
  switch($listing){
    case 'id':
      $order = 'p.products_id';
      break;
    case 'productname':
      $order = 'pd.products_name';
      break;
    case 'model':
      $order = 'p.products_model asc';
    default:
    case 'sort':
      $listing = 'sort';
      $order = 'x.sort_order asc';
  }
    ?>
    <table cellpadding="1" cellspacing="1" border="0" width="100%">
     <tr class="dataTableHeadingRow">
      <td class="dataTableHeadingContent" width="75" style="white-space: nowrap; padding: 0 5px 0 5px;"><?php
        echo ($listing=='id' ? tep_image(DIR_WS_IMAGES.'icons/arrow_down.gif', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px;"').TABLE_HEADING_PRODUCT_ID
                             : tep_image(DIR_WS_IMAGES.'icons/arrow_down_grey.png', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px; white-space: nowrap;"').'<a href="'.tep_href_link(CURRENT_PAGE, tep_get_all_get_params(array('listing')).'listing=id').'">'.  TABLE_HEADING_PRODUCT_ID . '</a>');
      ?></td>
      <td class="dataTableHeadingContent" style="white-space: nowrap; padding: 0 5px 0 5px;"><?php
        echo ($listing=='model'?tep_image(DIR_WS_IMAGES.'icons/arrow_down.gif', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px;"').TABLE_HEADING_PRODUCT_MODEL
                               :tep_image(DIR_WS_IMAGES.'icons/arrow_down_grey.png', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px; white-space: nowrap;"').'<a href="'.tep_href_link(CURRENT_PAGE, tep_get_all_get_params(array('listing')).'listing=model').'">'. TABLE_HEADING_PRODUCT_MODEL . '</a>');
      ?></td>
	    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_IMAGE;?>&nbsp;</td>
      <td class="dataTableHeadingContent">&nbsp;<?php
        echo ($listing=='productname'?tep_image(DIR_WS_IMAGES.'icons/arrow_down.gif', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px;"') . TABLE_HEADING_PRODUCT_NAME
                                     :tep_image(DIR_WS_IMAGES.'icons/arrow_down_grey.png', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px; white-space: nowrap;"').'<a href="'.tep_href_link(CURRENT_PAGE, tep_get_all_get_params(array('listing')).'listing=productname').'">'. TABLE_HEADING_PRODUCT_NAME . '</a>') ;
      ?></td>
	    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_PRICE;?>&nbsp;</td>
	    <td class="dataTableHeadingContent" style="white-space: nowrap; padding: 0 5px 0 5px;"><?php
        echo ($listing=='sort'?tep_image(DIR_WS_IMAGES.'icons/arrow_down.gif', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px;"') . TABLE_HEADING_PRODUCT_SORT
                                     :tep_image(DIR_WS_IMAGES.'icons/arrow_down_grey.png', '', '', '', 'style="display: table-cell;vertical-align: middle; padding-right: 3px; white-space: nowrap;"').'<a href="'.tep_href_link(CURRENT_PAGE, tep_get_all_get_params(array('listing')).'listing=sort').'">'. TABLE_HEADING_PRODUCT_SORT . '</a>') ;
      ?></td>
	   </tr>
<?php
    $products_query_raw = ' select p.products_id as products_id, p.products_price, p.products_image, p.products_model, pd.products_name, p.products_id, x.products_id as xproducts_id, x.xsell_id, x.sort_order, x.ID '.
                          ' from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd, '.TABLE_PRODUCTS_XSELL.' x '.
                          ' where x.xsell_id = p.products_id and x.products_id = "'.$_GET['add_related_product_ID'].'" and p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" '. $search_params .
                          ' order by '. $order;
    $products_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
	$sort_order_drop_array = array();
	for($i=1;$i<=$products_query_numrows;$i++){
	$sort_order_drop_array[] = array('id' => $i, 'text' => $i);
	}
    $products_query = tep_db_query($products_query_raw);
 while ($products = tep_db_fetch_array($products_query)){
?>
	 <tr bgcolor='#DFE4F4'>
	  <td class="dataTableContent" align="center">&nbsp;<?php echo $products['products_id'];?>&nbsp;</td>
	  <td class="dataTableContent" align="center">&nbsp;<?php echo $products['products_model'];?>&nbsp;</td>
	  <td class="dataTableContent" align="center">&nbsp;<?php echo ((is_file('../images/'.$products['products_image'])) ?  tep_image('../images/'.$products['products_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) : '<br>'.TEXT_NO_IMAGE.'<br>');?>&nbsp;</td>
	  <td class="dataTableContent" align="center">&nbsp;<?php echo $products['products_name'];?>&nbsp;</td>
	  <td class="dataTableContent" align="center">&nbsp;<?php echo $currencies->format($products['products_price']);?>&nbsp;</td>
	  <td class="dataTableContent" align="center">&nbsp;<?php echo tep_draw_pull_down_menu($products['products_id'], $sort_order_drop_array, $products['sort_order']);?>&nbsp;</td>
     </tr>
<?php
}
?>
    </table></form>
    <table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBoxContent">
     <tr>
      <td align="right" valign="bottom" colspan="2"><?php echo tep_image_submit('button_update.gif') . '&nbsp;<a href="'.tep_href_link(FILENAME_XSELL_PRODUCTS, 'men_id=catalog').'">' . tep_image_button('button_cancel.gif') . '</a>';?></td>
     </tr>
     <tr>
      <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
      <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID', 'action'))); ?></td>
     </tr>
    </table>
<?php
}
?>
<!-- body_text_eof //-->
  </td>
 </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
