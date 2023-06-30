<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="/">
    <html>
      <head>
        <title>Order Report</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"/>
        <script>
          function redirectToOrdersPage() {
            window.location.href = '/orders';
          }
        </script>
      </head>
      <body>
        <div class="container">
          <h1 class="mt-4">Order Report</h1>
          <table class="table table-bordered mt-4">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Order Date</th>
                <th>Order Items</th>
                <th>Total Price</th>
              </tr>
            </thead>
            <tbody>
              <xsl:for-each select="report/order">
                <tr>
                  <td><xsl:value-of select="id"/></td>
                  <td><xsl:value-of select="date"/></td>
                  <td>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Product Name</th>
                          <th>Product Description</th>
                          <th>Product Color</th>
                          <th>Product Storage</th>
                          <th>Product Price</th>
                          <th>Quantity</th>
                        </tr>
                      </thead>
                      <tbody>
                        <xsl:for-each select="orderItem">
                          <tr>
                            <td><xsl:value-of select="productName"/></td>
                            <td><xsl:value-of select="productDesc"/></td>
                            <td><xsl:value-of select="productColor"/></td>
                            <td><xsl:value-of select="productStorage"/></td>
                            <td><xsl:value-of select="productPrice"/></td>
                            <td><xsl:value-of select="quantity"/></td>
                          </tr>
                        </xsl:for-each>
                      </tbody>
                    </table>
                  </td>
                  <td>RM<xsl:value-of select="price"/></td>
                </tr>
              </xsl:for-each>
            </tbody>
          </table>
          <a href="#" class="btn btn-primary float-right" onclick="redirectToOrdersPage()">Go to Orders Page</a>
        </div>
      </body>
    </html>
  </xsl:template>

</xsl:stylesheet>
