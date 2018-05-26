== Installation ==

 * Purchase the extension and download the zip file from ShopPlugins.com
 * Login to your WordPress dashboard. Click on Plugins | Add New from the left hand menu
 * Click on the "Upload" option, then click "Browse" to select the zip file from your computer.
 * After the zip file has been selected press the "Install Now" button.
 * On the Plugins page, find the section for "WooCommerce Redirect Thank You" plugin and press "Activate"


== Usage ==

This plugin gives the store administrator to ability to redirect the customer to
a specific "Order Received" page

First, define a WordPress page that will be used as the custom order received page.
If you want the order details to be displayed on the page you can use the
shortcode [growdev_order_details].

Next, link the thank you page to a product. Go to the Edit Product page and
in the Custom Thank You Page sidebar select the page you just created.
Press Update to save the choice.

If you want to have a default Thank You page, you can go to WooCommerce > Settings and click on the Thank You tab.
Set the Global Thank You Page setting to override the WooCommerce order-received endpoint.

Whenever the product is purchased the customer will be redirected to the defined
page after the order is placed.

== Notes ==

1. If the payment is declined the customer will not be redirected to the custom thank you page.

2. If there are multiple products in the order that have a custom thank you page defined,
the redirect from the last product added to the cart will be used.

3. If the Global Thank You Page setting is defined and no products have a Custom Thank You page, then the customer
will be redirected to the global thank you page.

