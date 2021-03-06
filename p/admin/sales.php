<?php
require_once( "../db.php" );
require_once( "../transaction.php" );
require_once("../purchase.php");
require_once( "../book.php" );
require_once( "../user.php" );

function getOrders()
{

	$table = "";

        $db = Database::connect();
        $purchases = Purchase::getAllPurchasesForUsers( $db );
        $output = array();
        $ans = array();
        $count = 1;
        foreach( $purchases as $t ){
        	if($count > 10)
        		break;
        	$pid = $count++;
        	
	        $user = User::getUserById( $db, $t["user_id"] );
		$books = array();
		$totalmrp = 0;
		$totalsp = 0;
		for($i=0;$i<count($t["books"]);$i++){
 	        	$book = Book::getBookById( $db, $t["books"][$i] );
 	        	array_push($books,$book->getName());
 	        	$totalmrp += $book->getMarkedPrice();
 	        	$totalsp += $book->getSellingPrice();
 	        }
	        $output[ "id" ] = $pid;
	        $output[ "uid" ] = $t["user_id"];
		$output[ "usn" ] = $user->getUsn();
	        $output[ "customer" ] = $user->getName();
		$output[ "sem" ] = $user->getSemester();
//		$output[ "section" ] = $user->getSection();
		$output[ "phone" ] = $user->getMobileNo();
	        $output[ "email_id" ] = $user->getEmail();
	        $output[ "phone" ] = $user->getMobileNo();
	        $output[ "book" ] = implode(", ",$books);
	        $output[ "mrp" ] = $totalmrp;
	        $output[ "sp" ] = $totalsp;
	        $output[ "uid" ] = $t["user_id"];
	        array_push( $ans, $output );
    }
    
	$table = "";
	$counter = 1;
	foreach( $ans as $a ){
	$table .="<tr>";
	$table .="<td>".$a[ "id" ]."</td>";
	$table .="<td>".$a[ "uid" ]."</td>";
	$table .="<td>".$a[ "usn" ]."</td>";
        $table .="<td>".$a[ "customer" ]."</td>";
	$table .="<td>".$a[ "email_id" ]."</td>";
        $table .="<td>".$a[ "phone" ]."</td>";
        $table .="<td>".$a[ "book" ]."</td>";
	$table .="<td>".$a[ "mrp" ]."</td>";
	$table .="<td>".$a[ "sp" ]."</td>";

	$table .="</tr>";
	}
	return $table;
}



function getDeliverTable(){

	$sales = "";
	$sales =<<<END
	    <div class="span12" style="margin-bottom:40px;">
<!--	    	  <div class="row" style="margin-top:20px;">
	    	  	<div class="span3 offset9">
				<button class="btn btn-success btn-small">Insert</button>
				<button class="btn btn-danger btn-small">Delete</button>
				<button class="btn btn-info btn-small">Print Invoice</button>
			</div>
		  </div>-->
		  <hr />
		  <div class="row">
		      <table class="table table-striped table-condensed" style="margin-bottom:40px;">
			<legend>Books to be Delivered</legend>
			<thead>
				<tr>
<!--				  <th><input type="checkbox" id="selectall" title="Select All"/></th> -->
				  <th>Sl No.</th>
				  <!--<th>Order ID</th>-->
				  <th>USN</th>
				  <th>Student Name</th>
                 		  <th> Semester </th>
                                  <!--<th> Section </th>-->
				  <th>Phone No.</th>
				  <th>Email-Id</th>
                  		  <th>Books</th>
                  		  <!--<th>Edition </th>
                  		  <th>Publication </th>
  				  <th>Book ID</th>-->
				  <th>Total MRP</th>
				  <th>Total SP</th>
				  <th>Action</th>
				</tr>
			</thead>			  
			<tbody>
END;
		$sales .= getDeliveryTableContent();
	$sales .=<<<END

  			</tbody>
			</table>
<!--			<div class="pagination pagination-small">
			    <ul>
				    <li><a href="#">Prev</a></li>
  				    <li><a href="#">1</a></li>
				    <li><a href="#">2</a></li>
				    <li><a href="#">3</a></li>
 				    <li><a href="#">4</a></li>
				    <li><a href="#">Next</a></li>
			    </ul>
		        </div> -->
		      </div><!-- end of row -->
		    </div><!-- end of span12 -->
END;
	return $sales;
}

function getDeliveryTableContent(){
	$table = "";

        $db = Database::connect();
        $purchases = Purchase::getAllPurchasesForUsers( $db );
        $output = array();
        $ans = array();
        $count = 1;
        foreach( $purchases as $t ){
        	$pid = $count++;
	        $user = User::getUserById( $db, $t["user_id"] );
		$books = array();
		$totalmrp = 0;
		$totalsp = 0;
		for($i=0;$i<count($t["books"]);$i++){
 	        	$book = Book::getBookById( $db, $t["books"][$i] );
 	        	array_push($books,$book->getName());
 	        	$totalmrp += $book->getMarkedPrice();
 	        	$totalsp += $book->getSellingPrice();
 	        }
	        $output[ "uid" ] = $t["user_id"];
	        $output[ "id" ] = $pid;
		$output[ "usn" ] = $user->getUsn();
	        $output[ "customer" ] = $user->getName();
		$output[ "sem" ] = $user->getSemester();
//		$output[ "section" ] = $user->getSection();
		$output[ "phone" ] = $user->getMobileNo();
	        $output[ "email_id" ] = $user->getEmail();
	        $output[ "phone" ] = $user->getMobileNo();
	        $output[ "book" ] = implode(", ",$books);
	        $output[ "mrp" ] = $totalmrp;
	        $output[ "sp" ] = $totalsp;
	        $output[ "uid" ] = $t["user_id"];
	        array_push( $ans, $output );
    }
    
	$table = "";
	$counter = 1;
	foreach( $ans as $a ){
	$table .="<tr><!--<td><input type='checkbox' class='checkbox' title='Select'  id='" . $a[ "uid" ] . "'/></td>--><td>".$a[ "id" ]."</td>";
	$table .="<td>".$a[ "usn" ]."</td>";
        $table .="<td>".$a[ "customer" ]."</td>";
        $table .="<td>".$a[ "sem" ]."</td>";	
  //      $table .="<td>".$a[ "section" ]."</td>";
        $table .="<td>".$a[ "phone" ]."</td>";
	$table .="<td>".$a[ "email_id" ]."</td>";
        $table .="<td>".$a[ "book" ]."</td>";
	$table .="<td>".$a[ "mrp" ]."</td>";
	$table .="<td>".$a[ "sp" ]."</td>";
	$table .="<td><a href='view.php?q=deliver&uid=" . $a["uid"] . "' title='View Book Details'>View Details</a> <!--| <a href='edit.php?q=approve&uid=" . $a["uid"] . "' title='Edit Book Details'>Edit</a>--></td></tr>";

	$table .="</tr>";
	}
	return $table;
}

function getViewDeliverTable($uid){

        $db = Database::connect();
        $purchases = Purchase::getPurchasesPerUser( $db, $uid );
        $output = array();
        $ans = array();
        foreach( $purchases as $t ){
	        $user = User::getUserById( $db, $uid );
		$books = array();
		$totalmrp = 0;
		$totalsp = 0;
		for($i=0;$i<count($t["books"]);$i++){
 	        	$book = Book::getBookById( $db, $t["books"][$i] );
 	        	array_push($books,$book->getName());
 	        	$totalmrp += $book->getMarkedPrice();
 	        	$totalsp += $book->getSellingPrice();
 	        }
 	        $output[ "allbooks" ] = implode(",",$t["books"]);
	        $output[ "uid" ] = $uid;
		$output[ "usn" ] = $user->getUsn();
	        $output[ "customer" ] = $user->getName();
		$output[ "sem" ] = $user->getSemester();
//		$output[ "section" ] = $user->getSection();
		$output[ "phone" ] = $user->getMobileNo();
	        $output[ "email_id" ] = $user->getEmail();
	        $output[ "phone" ] = $user->getMobileNo();
	        $output[ "book" ] = implode(", ",$books);
	        $output[ "mrp" ] = $totalmrp;
	        $output[ "sp" ] = $totalsp;
	        array_push( $ans, $output );
    }


	$table = "";
	$statistics = "";
	$buyerContent = "";
	$bookContent = "";
	foreach( $ans as $a ){
		$userId = $a["uid"];
		$usn = $a["usn"];
		$name = $a["customer"];
		$email = $a["email_id"];
		$phone = $a["phone"];
		$book = $a["book"];
		$totalmrp = $a["mrp"];
		$totalsp = $a["sp"];

		$buyerContent .=<<<END
	    <form class="form-horizontal">
		    <fieldset>
			    <legend>Buyer Details</legend>
     			    <div class="control-group">
			    	<label class="control-label">User ID</label>
			    	<div class="controls">
				    	<label class="control-label">$userId</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">USN</label>
			    	<div class="controls">
				    	<label class="control-label">$usn</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Name</label>
			    	<div class="controls">
				    	<label class="control-label">$name</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">E-mail</label>
			    	<div class="controls">
				    	<label class="control-label">$email</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Phone Number</label>
			    	<div class="controls">
				    	<label class="control-label">$phone</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Books Purchased</label>
			    	<div class="controls">
				    	<label class="control-label">$book</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Total MRP</label>
			    	<div class="controls">
				    	<label class="control-label">$totalmrp</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Total Selling Price</label>
			    	<div class="controls">
				    	<label class="control-label">$totalsp</label>
				</div>
			    </div>
		    </fieldset>
	    </form>
END;

		$allbooks = array();
		$allbooks = explode(",",$a["allbooks"]);
		for($i=0;$i<count($allbooks);$i++){
 	        	$onebook = Book::getBookById( $db, $allbooks[$i] );
			$bookId = $allbooks[$i];
			$bname = $onebook->getName();
			$authorlist = array();
			$authorlist = Author::getAuthorByBook($db,array($onebook));
			$authors = implode(",",$authorlist[0]);
			$edition = $onebook->getEdition();
			$publication = $onebook->getPublication();
			$condition = $onebook->getCondition();
			$desc = $onebook->getDescription();
			$mrp = $onebook->getMarkedPrice();
			$sp = $onebook->getSellingPrice();
			$discount = $onebook->getDiscount();
			


			$count = $i + 1;
			$bookContent .=<<<END
	    <form class="form-horizontal">
		    <fieldset>
			    <legend>$count : $bname</legend>
     			    <div class="control-group">
			    	<label class="control-label">Book ID</label>
			    	<div class="controls">
				    	<label class="control-label">$bookId</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Book Name</label>
			    	<div class="controls">
				    	<label class="control-label">$bname</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Authors</label>
			    	<div class="controls">
				    	<label class="control-label">$authors</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Edition</label>
			    	<div class="controls">
				    	<label class="control-label">$edition</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Publication</label>
			    	<div class="controls">
				    	<label class="control-label">$publication</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Condition</label>
			    	<div class="controls">
				    	<label class="control-label">$condition</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Description</label>
			    	<div class="controls">
				    	<label class="control-label">$desc</label>
			    	</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Marked Price</label>
			    	<div class="controls">
				    	<label class="control-label">$mrp</label>
				</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Selling Price</label>
			    	<div class="controls">
				    	<label class="control-label">$sp</label>
				</div>
			    </div>
     			    <div class="control-group">
			    	<label class="control-label">Discount</label>
			    	<div class="controls">
				    	<label class="control-label">$discount</label>
				</div>
			    </div>
		    </fieldset>
	    </form>
END;
		}

	}


	$table .=<<<END
	    <ul class="nav nav-tabs" id="myTab">
    <li class="active" id="buyerTab"><a href="#buyer" onclick="changeTab('buyer');">Buyer Details</a></li>
    <li id="bookTab"><a href="#book" onclick="changeTab('book');">Book Details</a></li>
    </ul>
     
    <div class="tab-content">
    <div class="tab-pane active" id="buyer">$buyerContent</div>
    <div class="tab-pane" id="book">$bookContent</div>
    </div>
     
    <script>
    function changeTab(tab){
    	switch(tab){
    		case "buyer":
    			$("#buyerTab").addClass("active");
    			$("#bookTab").removeClass("active");

    			$("#buyer").addClass("active");
    			$("#book").removeClass("active");
    			
    			break;
    		case "book":
    			$("#bookTab").addClass("active");
    			$("#buyerTab").removeClass("active");

    			$("#book").addClass("active");
    			$("#buyer").removeClass("active");
    			
    			break;
    		default:
    			$("#buyerTab").addClass("active");
    			$("#bookTab").removeClass("active");

    			$("#buyer").addClass("active");
    			$("#book").removeClass("active");
    			break;
    			
    	}
    	return false;
    }
    			$("#buyerTab").addClass("active");
    			$("#bookTab").removeClass("active");

    			$("#buyer").addClass("active");
    			$("#book").removeClass("active");
     </script>
END;
	return $table;
}

function getEditDeliverForm($bookId){
	$table = "";
	$table .=<<<END
	    <ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#buyer">Buyer Details</a></li>
    <li><a href="#book">Book Details</a></li>
    <li><a href="#seller">Seller Details</a></li>
    </ul>
     
    <div class="tab-content">
    <div class="tab-pane active" id="buyer">...</div>
    <div class="tab-pane" id="book">...</div>
    <div class="tab-pane" id="seller">...</div>
    </div>
     
    <script>
    $(function () {
    $('#myTab a:last').tab('show');
    })
    </script>
END;
	return $table;
}


?>
