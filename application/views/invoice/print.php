<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Indian Medical Bill</title>
    <link href="<?php echo base_url(); ?>css/print.css" rel="stylesheet">
  </head>
  <body>
    <?php
      if(!isset($invoice[0]))
        return;
    ?>
    <div class="invoice_section">
      <div class="invoice_container">
        <div class="invoice_top">
          <img src="<?php echo base_url();?>/images/invoice/logo.png" alt="" class="logo">
          <span>Medical Bill</span>
          <div class="clearfix"></div>
        </div>
        <div class="invoice_header">
          <div class="pull_left address">
              <h2>Bharat Care Medical Centre</h2>
              <p>
                2nd Floor, Health Plaza,<br>
                MG Road, Pune, Maharashtra 411001,<br>
                India
              </p>
          </div>
          <div class="pull_right">
            <ul class="contact_list">
              <li>
                <span>Phone</span>
                <p>+91 98765 43210 <br> +91 91234 56780</p>
              </li>
              <li>
                <span>Email</span>
                <p>billing@bharatcare.in <br>care@bharatcare.in</p>
              </li>
            </ul>
            <div class="invoice_no_date">
              <div>
                <h2>#Bill No</h2>
                <p><?php echo $invoice[0]->id; ?></p>
              </div>
              <div>
                <h2>#Bill Date</h2>
                <p><?php echo $invoice[0]->date; ?></p>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="invoice_items">
          <table>
            <tr class="items_header">
              <th width="40">S.No.</th>
              <th>MEDICAL DESCRIPTION</th>
              <th width="150">AMOUNT (INR)</th>
            </tr>
            <?php
              $items = json_decode($invoice[0]->data);
              if($items){
                foreach ($items as $key => $value) {
                  ?>
                  <tr>
                    <td><?php echo $key+1; ?></td>
                    <td><?php echo $value->label; ?></td>
                    <td>&#8377; <?php echo number_format((float)$value->price, 2); ?></td>
                  </tr>
                  <?php
                }
              }
            ?>
          </table> 
        </div>
        <div class="total">
          <h2>GRAND TOTAL <span>:</span>&#8377; <?php echo number_format((float)$invoice[0]->total, 2); ?></h2>
        </div>
        <div class="footer">
        </div>
      </div>
    </div>
    <div class="print_btn">
      <a href="javascript:window.print();">Print</a>
    </div>
  </body>
</html>
