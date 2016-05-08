<div class="small-12 large-8 columns">
    <table  style="width: 100%">
        <tr>
            <td style="width: 150px">Address</td>
            <td>
                <textarea rows="4" cols="60" name="dropin_listing_address"><?php echo $listing_address; ?></textarea>
            </td>
        </tr>
        <tr>
            <td style="width: 150px">Area</td>
            <td>
                <textarea rows="1" cols="60" name="dropin_listing_area"><?php echo $listing_area; ?></textarea>
            </td>
        </tr>
        <tr>
            <td style="width: 150px">Phone</td>
            <td>
                <input type="text" size="80"
                       name="dropin_listing_phone"
                       value="<?php echo $listing_phone; ?>" />
            </td>
        </tr>
        <tr>
            <td style="width: 150px">Contact Name</td>
            <td>
                <input type="text" size="80"
                       name="dropin_listing_contact_name"
                       value="<?php echo $listing_contact_name; ?>" />
            </td>
        </tr>
        <tr>
            <td style="width: 150px">Email</td>
            <td>
                <input type="text" size="80"
                       name="dropin_listing_email"
                       value="<?php echo $listing_email; ?>" />
            </td>
        </tr>
        <tr>
            <td style="width: 150px">Geo Latitude</td>
            <td>
                <input type="text" size="80"
                       name="dropin_listing_geo_lat"
                       value="<?php echo $listing_geo_lat; ?>" />
            </td>
        </tr>
        <tr>
            <td style="width: 150px">Geo Longitud</td>
            <td>
                <input type="text" size="80"
                       name="dropin_listing_geo_lon"
                       value="<?php echo $listing_geo_lon; ?>" />
            </td>
        </tr>
        <tr>
            <td style="width: 150px">Website</td>
            <td>
                <input type="text" size="80"
                       name="dropin_listing_website"
                       value="<?php echo $listing_website; ?>" />
            </td>
        </tr>
        <tr>
            <td style="width: 150px">Contact By</td>
            <td>
                <input type="text" size="80"
                       name="dropin_listing_contactby"
                       value="<?php echo $listing_contactby; ?>" />
            </td>
        </tr>
        <tr>
            <td style="width: 150px">Opening Times</td>
            <td>
              <textarea rows="4" cols="60" name="dropin_listing_opening_times"><?php echo $listing_opening_times; ?></textarea>
            </td>
        </tr>
        <tr>
            <td style="width: 150px">Who is eligible</td>
            <td>
                <input type="text" size="80"
                       name="dropin_listing_eligible"
                       value="<?php echo $listing_eligible; ?>" />
            </td>
        </tr>
        <tr>
            <td style="width: 150px">Transport to get there</td>
            <td>
                <input type="text" size="80"
                       name="dropin_listing_transport"
                       value="<?php echo $listing_transport; ?>" />
            </td>
        </tr>
        <tr>
            <td style="width: 150px">Listing Destination</td>
            <td>
                <select style="width: 300px"
                        name="dropin_listing_destination">
                    <option value="0" selected >Select destination</option>
                    <option value="1" selected >Don't Publish</option>
                    <option value="2" selected >Only Website</option>
                    <option value="3" selected >Website and Printing</option>
                </select>
            </td>
        </tr>
    </table>
</div>
