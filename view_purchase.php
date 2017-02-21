<?php
include_once("init.php");

?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>GS - Achats</title>

    <!-- Stylesheets -->
    <!---->
    <link rel="stylesheet" href="css/style.css">

    <!-- Optimize for mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- jQuery & JS files -->
    <?php include_once("tpl/common_js.php"); ?>
    <script src="js/script.js"></script>
    <script src="js/view_purchase.js"></script>
	<script>
	//var c=sessionStorage.getItem('checked-checkboxesviewpurchase');
	//alert(c);
</script>
</head>
<body>

<!-- TOP BAR -->
<?php include_once("tpl/top_bar.php"); ?>
<!-- end top-bar -->


<!-- HEADER -->
<div id="header-with-tabs">

    <div class="page-full-width cf">

        <ul id="tabs" class="fl">
            <li><a href="dashboard.php" class=" dashboard-tab">Acceuil</a></li>
            <li><a href="view_sales.php" class="sales-tab">Ventes</a></li>
            <li><a href="view_customers.php" class=" customers-tab">Clients</a></li>
            <li><a href="view_purchase.php" class="active-tab purchase-tab">Achats</a></li>
            <li><a href="view_supplier.php" class=" supplier-tab">Fournisseurs</a></li>
            <li><a href="view_product.php" class=" stock-tab">Stocks / Produits</a></li>
            <li><a href="view_payments.php" class="payment-tab">Paiments </a></li>
            <li><a href="view_report.php" class="report-tab">Rapports</a></li>
        </ul>
        <!-- end tabs -->

       

    </div>
    <!-- end full-width -->

</div>
<!-- end header -->


<!-- MAIN CONTENT -->
<div id="content">

    <div class="page-full-width cf">

        <div class="side-menu fl">

            
            <ul>
                <li><a href="add_purchase.php">Ajouter Achat</a></li>
                <li><a href="view_purchase.php">Afficher Achats </a></li>

            </ul>
        </div>
        <!-- end side-menu -->

        <div class="side-content fr">

            <div class="content-module">

               

                <div class="content-module-main cf">


                    <table>
                        <form action="" method="post" name="search">
                            <input name="searchtxt" type="text" class="round my_text_box" placeholder="Rechercher">
                            &nbsp;&nbsp;<input name="Search" type="submit" class="my_button round blue   text-upper"
                                               value="ok">
                        </form>
                        <form action="" method="get" name="limit_go">
                            Achats par page<input name="limit" type="text" class="round my_text_box" id="search_limit"
                                                  style="margin-left:5px;"
                                                  value="<?php if (isset($_GET['limit'])) echo $_GET['limit']; else echo "10"; ?>"
                                                  size="3" maxlength="3">
                            <input name="go" type="button" value="Ok" class=" round blue my_button  text-upper"
                                   onclick="return confirmLimitSubmit()">
                        </form>

                        <form name="deletefiles" action="delete.php" method="post">

                            <input type="hidden" name="table" value="stock_entries">
                            <input type="hidden" name="return" value="view_purchase.php">
                         <!--   <input type="button" name="selectall" value="SelectAll"
                                   class="my_button round blue   text-upper" onClick="checkAll()"
                                   style="margin-left:5px;"/>
                            <input type="button" name="unselectall" value="DeSelectAll"
                                   class="my_button round blue   text-upper" onClick="uncheckAll()"
                                   style="margin-left:5px;"/>-->
                            <input name="dsubmit" type="button" value="Supprimer Selection"
                                   class="my_button round blue   text-upper" style="margin-left:5px;"
                                   onclick="return confirmDeleteSubmit()"/>
 <!--<input type="button" name="Deleteall" value="Delect All Records"
                                   class="my_button round blue   text-upper" onClick="deleteall()"
                                   style="margin-left:5px;" id="cancelall"/>-->

                            <table>
                                <?php


                                $SQL = "SELECT DISTINCT(stock_id) FROM  stock_entries where type='entry'";

                                if (isset($_POST['Search']) AND trim($_POST['searchtxt']) != "") {

                                    //$SQL = "SELECT DISTINCT(stock_id) FROM  stock_entries WHERE stock_name LIKE '%" . $_POST['searchtxt'] . "%' OR stock_supplier_name LIKE '%" . $_POST['searchtxt'] . "%' OR stock_id LIKE '%" . $_POST['searchtxt'] . "%' OR date LIKE '%" . $_POST['searchtxt'] . "%' OR type LIKE '%" . $_POST['searchtxt'] . "%' AND type='entry'";
                                    $SQL = "SELECT DISTINCT(stock_id) FROM  stock_entries WHERE stock_name LIKE '%" . $_POST['searchtxt'] ."%' ORDER BY id DESC ";

                                }

                                $tbl_name = "stock_entries";        //your table name

                                // How many adjacent pages should be shown on each side?

                                $adjacents = 3;


                                /*

                                   First get total number of rows in data table.

                                   If you have a WHERE clause in your query, make sure you mirror it here.

                                */

                                $query = "SELECT COUNT(DISTINCT stock_id) as num FROM $tbl_name where type='entry'";
                                if (isset($_POST['Search']) AND trim($_POST['searchtxt']) != "") {

                                    $query = "SELECT COUNT(DISTINCT stock_id) as num FROM stock_entries WHERE stock_name LIKE '%" . $_POST['searchtxt'] . "%' OR stock_supplier_name LIKE '%" . $_POST['searchtxt'] . "%' OR stock_id LIKE '%" . $_POST['searchtxt'] . "%' OR date LIKE '%" . $_POST['searchtxt'] . "%' OR type LIKE '%" . $_POST['searchtxt'] . "%' and type='entry'";


                                }
                                $total_pages = mysqli_fetch_array(mysqli_query($db->connection, $query));

                                $total_pages = $total_pages['num'];


                                /* Setup vars for query. */

                                $targetpage = "view_stock_entries.php";    //your file name  (the name of this file)

                                $limit = 10;                                //how many items to show per page
                                if (isset($_GET['limit']))
                                    $limit = $_GET['limit'];

                                $page = isset($_GET['page']) ? $_GET['page'] : 0;

                                if ($page)

                                    $start = ($page - 1) * $limit;            //first item to display on this page

                                else

                                    $start = 0;                                //if no page var is given, set start to 0


                                /* Get data. */
                                
                                //$sql = "SELECT DISTINCT(stock_id) FROM stock_entries where type='entry' ORDER BY date desc LIMIT $start, $limit  ";
                                $sql = "SELECT * FROM stock_entries ORDER BY id desc LIMIT $start, $limit  ";
                                if (isset($_POST['Search']) AND trim($_POST['searchtxt']) != "") {

                                    //$sql = "SELECT DISTINCT(stock_id) FROM stock_entries WHERE stock_name LIKE '%" . $_POST['searchtxt'] . "%' OR stock_supplier_name LIKE '%" . $_POST['searchtxt'] . "%' OR stock_id LIKE '%" . $_POST['searchtxt'] . "%' OR date LIKE '%" . $_POST['searchtxt'] . "%' OR type LIKE '%" . $_POST['searchtxt'] . "%' and type='entry' ORDER BY date desc LIMIT $start, $limit";
                                    $sql = "SELECT * FROM stock_entries WHERE stock_name LIKE '%" . $_POST['searchtxt'] . "%'  ORDER BY id desc LIMIT $start, $limit";

                                }

                                $result = mysqli_query($db->connection, $sql);


                                /* Setup page vars for display. */

                                if ($page == 0) $page = 1;                    //if no page var is given, default to 1.

                                $prev = $page - 1;                            //previous page is page - 1

                                $next = $page + 1;                            //next page is page + 1

                                $lastpage = ceil($total_pages / $limit);        //lastpage is = total pages / items per page, rounded up.

                                $lpm1 = $lastpage - 1;                        //last page minus 1


                                /*

                                    Now we apply our rules and draw the pagination object.

                                    We're actually saving the code to a variable in case we want to draw it more than once.

                                */

                                $pagination = "";

                                if ($lastpage > 1) {

                                    $pagination .= "<div >";

                                    //previous button

                                    if ($page > 1)

                                        $pagination .= "<a href=\"view_purchase.php?page=$prev&limit=$limit\" class=my_pagination >Précédent</a>";

                                    else

                                        $pagination .= "<span class=my_pagination>Précédent</span>";


                                    //pages

                                    if ($lastpage < 7 + ($adjacents * 2))    //not enough pages to bother breaking it up

                                    {

                                        for ($counter = 1; $counter <= $lastpage; $counter++) {

                                            if ($counter == $page)

                                                $pagination .= "<span class=my_pagination>$counter</span>";

                                            else

                                                $pagination .= "<a href=\"view_purchase.php?page=$counter&limit=$limit\" class=my_pagination>$counter</a>";

                                        }

                                    } elseif ($lastpage > 5 + ($adjacents * 2))    //enough pages to hide some

                                    {

                                        //close to beginning; only hide later pages

                                        if ($page < 1 + ($adjacents * 2)) {

                                            for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {

                                                if ($counter == $page)

                                                    $pagination .= "<span class=my_pagination>$counter</span>";

                                                else

                                                    $pagination .= "<a href=\"view_purchase.php?page=$counter&limit=$limit\" class=my_pagination>$counter</a>";

                                            }

                                            $pagination .= "...";

                                            $pagination .= "<a href=\"view_purchase.php?page=$lpm1&limit=$limit\" class=my_pagination>$lpm1</a>";

                                            $pagination .= "<a href=\"view_purchase.php?page=$lastpage&limit=$limit\" class=my_pagination>$lastpage</a>";

                                        } //in middle; hide some front and some back

                                        elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {

                                            $pagination .= "<a href=\"view_purchase.php?page=1&limit=$limit\" class=my_pagination>1</a>";

                                            $pagination .= "<a href=\"view_purchase.php?page=2&limit=$limit\" class=my_pagination>2</a>";

                                            $pagination .= "...";

                                            for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {

                                                if ($counter == $page)

                                                    $pagination .= "<span  class=my_pagination>$counter</span>";

                                                else

                                                    $pagination .= "<a href=\"view_purchase.php?page=$counter&limit=$limit\" class=my_pagination>$counter</a>";

                                            }

                                            $pagination .= "...";

                                            $pagination .= "<a href=\"view_purchase.php?page=$lpm1&limit=$limit\" class=my_pagination>$lpm1</a>";

                                            $pagination .= "<a href=\"view_purchase.php?page=$lastpage&limit=$limit\" class=my_pagination>$lastpage</a>";

                                        } //close to end; only hide early pages

                                        else {

                                            $pagination .= "<a href=\"$view_purchase.php?page=1&limit=$limit\" class=my_pagination>1</a>";

                                            $pagination .= "<a href=\"$view_purchase.php?page=2&limit=$limit\" class=my_pagination>2</a>";

                                            $pagination .= "...";

                                            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {

                                                if ($counter == $page)

                                                    $pagination .= "<span class=my_pagination >$counter</span>";

                                                else

                                                    $pagination .= "<a href=\"$targetpage?page=$counter&limit=$limit\" class=my_pagination>$counter</a>";

                                            }

                                        }

                                    }


                                    //next button

                                    if ($page < $counter - 1)

                                        $pagination .= "<a href=\"view_purchase.php?page=$next&limit=$limit\" class=my_pagination>Suivant</a>";

                                    else

                                        $pagination .= "<span class= my_pagination >Suivant</span>";

                                    $pagination .= "</div>\n";

                                }

                                ?>
                                <tr>
                                    <th>No</th>
                                    <th>Id Stock</th>
                                    <th>Nom Stock</th>
                                    <th>Date</th>
                                    <th>Fournisseur</th>
                                    <th>Montant</th>
                                    <th>Modifier/Supprimer</th>
                                    <th>Selectionner</th>
                                    
                                    
                                </tr>

                                <?php $i = 1;
                                $no = $page - 1;
                                $no = $no * $limit;

																//Count number of records
								$co=0;
								$co1=0;
								$s=mysqli_query($db->connection, "select * from stock_entries");
								while($r= mysqli_fetch_array($s))
								{
									$co++;
								}
								
								
                                while ($row = mysqli_fetch_array($result)) {
                                   // $entryid = $row['stock_id'];
								   $co1++; 
                                    //$line = $db->queryUniqueObject("SELECT * FROM stock_entries WHERE stock_id='$entryid' ");
                                   // $mysqldate = $line->date;
                                    //$phpdate = strtotime($mysqldate);
                                   // $phpdate = date("d/m/Y", $phpdate);
                                    ?>
                                    <tr id='tr<?php echo $row['id']; ?>'>
                                        
                                        <td> <?php echo $no + $i; ?></td>
                                        <td><?php echo $row['stock_id']; ?></td>
                                       <td><?php echo $row['stock_name']; ?></td>
                                       <td><?php echo $row['date']; ?></td>
                                       <td><?php echo $row['stock_supplier_name']; ?></td>
                                       <td><?php echo $row['total']; ?></td>
                                        <td>
                                            <a href="update_purchase.php?sid=<?php echo $row['id']; ?>&table=stock_entries&return=view_purchase.php"
                                               class="table-actions-button ic-table-edit">
                                            </a>
                                            <a onclick="return confirmSubmit()"
                                               href="delete.php?id=<?php echo $row['id']; ?>&table=stock_entries&return=view_purchase.php"
                                               class="table-actions-button ic-table-delete"></a>
                                        </td>
                                        <td><input type="checkbox"
                                                   value="<?php echo isset($row['id']) ? $row['id'] : 0; ?>"
                                                   name="checklist[]" id="<?php echo $row['id']; ?>"/></td>
                                        
                                    </tr>
                                    <?php $i++;
                                } ?>
                                <tr>
                                <table>
                                    <tr>
                                        <td align='right'style="width:20%"><?php $end = $no + $co1; ?>
                                             <?php if($end !=''){?>
                                            De <?php echo $no + 1; ?> a <?php echo $end; ?> sur <?php echo $co; ?></td><td >&nbsp;</td><td><?php echo $pagination; ?></td>
                                    <?php }else{?>
                                        
                                        De <?php echo $no; ?> a <?php echo $end; ?>sur <?php echo $co; ?></td><td >&nbsp;</td><td><?php echo $pagination; ?></td>
                                    <?php }?>
                                    </tr>


                                </table> 
                            </table>
                        </form>


                </div>
            </div>
            <div id="footer">
                

            </div>
            <!-- end footer -->

</body>
</html> 