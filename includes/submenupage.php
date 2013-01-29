<div class="wrap">
	<?php screen_icon('options-general'); ?>
	<h2>Global Settings for nLinks</h2>
	
	<?php 
		if($_POST['aLinks-options-save'] == "Y"):
			echo "<div class=\"updated\"><p>Saved.....</p></div>";
		endif;
	?>
	
	<form action="" method="post">
		<input type="hidden" name="aLinks-options-save" value="Y" />
		<table class="form-table">
			
			<tr>
				<th> <label for="aLinks-maximumLinksperpost-bal"> Maximum Links Per Post</label> </th>
				<td> <input type="text" name="aLinks-maximumLinksperpost-bal" id="aLinks-maximumLinksperpost-bal" value="<?php echo $options['max_link_p_post_bal'];?>" /> </td>
				<td colspan="2"> <code> -1 is for the unlimited link</code> </td>
			</tr>
			
			
			<tr>
				<th> <label for="aLinks-maximumLinksperpost"> Maximum Links Per Post Per KeyPhrase</label> </th>
				<td> <input type="text" name="aLinks-maximumLinksperpost" id="aLinks-maximumLinksperpost" value="<?php echo $options['max_link_p_post'];?>" /> </td>
				<td colspan="2"> <code> -1 is for the unlimited link</code> </td>
			</tr>
			
			
			
			<tr>
				<th> <label for="aLinks-maximumLinks"> Maximum Links for a KeyPhrase throughout the site</label> </th>
				<td> <input type="text" name="aLinks-maximumLinks" id="aLinks-maximumLinks" value="<?php echo $options['max_links'];?>" /> </td>
				<td colspan="2"> <code> -1 is for the unlimited link</code> </td>
			</tr>
			
			<tr>
				<th> <label for="aLinks-radomizeLinks"> Want to randomize the links?</label> </th>
				<td> <input <?php checked('Y', $options['randomize']); ?> type="checkbox" name="aLinks-radomizeLinks" value="Y"> </td>
				<td colspan="2"> <code> Select this option to randomize the position links within a posts if mulitple links are associated with a single KeyPhrase </code> </td>
			</tr>
			
			<tr> <td><h4> Raw URL Link Settings  </h4> </td> </tr>
			
			<tr>
				<td> Select a type </td>
				<td> 
					<select name="type">
						<option <?php selected('1', $options['type']); ?> value="1">hours</option>
						<option <?php selected('2', $options['type']); ?> value="2">days</option>
						<option <?php selected('3', $options['type']); ?> value="3">minutes</option>
					</select>
				</td>
			</tr>
			
			<tr>
				
				<td colspan = "3"> 
					<p><input <?php checked('2', $options['even']);?> id="nLinks_raw_url_even" type="checkbox" name="nLinks_raw_url_even" value="2" /> <label for="nLinks_raw_url_even"> Even </label> (<code>Creates Raw URL links on even intervals</code>) </p>
					<p> <input <?php checked('1', $options['odd']);?> id="nLinks_raw_url_odd" type="checkbox" name="nLinks_raw_url_odd" value="1" /> <label for="nLinks_raw_url_odd"> Odd  </label> (<code>Creates Raw URL links on odd intervals</code>)</p>
				</td>
				
			</tr>
			
			<tr>
				<td> <input type="submit" value="Save" class="button-primary" /> </td>
			</tr>
		</table>
	</form>
</div>