<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>Company</th>
			<th>Shares</th>
			<th>High</th>
			<th>Low</th>
			<th>Buy</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		 <?php
        include "/php/config.php";
		$res = $conn->query("select * from ");
		while ($row = $res->fetch_assoc());
        ?>
        <tr>
        	<td><?php echo $row ['Company']; ?></td>
        	<td><?php echo $row ['Shares']; ?></td>
        	<td><?php echo $row ['High']; ?></td>
        	<td><?php echo $row ['Low']; ?></td>
        	<td><?php echo $row ['Buy']; ?></td>
            <td><a href="<?php echo $row ['Action'];?>"></a></td>
        </tr>
	</tbody>
</table>