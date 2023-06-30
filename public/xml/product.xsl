<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html" indent="yes" />

  <xsl:template match="/">
    <html>
      <head>
        <title>Product List</title>
        <style>
          @import url("https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css");
        </style>
      </head>
      <body>
        <div class="container">
          <h1 class="mt-4">Product List</h1>
          <table class="table">
            <thead class="thead-dark">
              <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Product Description</th>
                <th>Variations</th>
              </tr>
            </thead>
            <tbody>
              <xsl:for-each select="products/product">
                <tr>
                  <td><xsl:value-of select="id" /></td>
                  <td><xsl:value-of select="productName" /></td>
                  <td><xsl:value-of select="productDesc" /></td>
                  <td>
                    <table class="table table-bordered">
                      <thead class="thead-light">
                        <tr>
                          <th>Product Storage</th>
                          <th>Product Color</th>
                          <th>Quantity</th>
                          <th>Product Price</th>
                          <th>Product Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <xsl:for-each select="variation">
                          <tr>
                            <td><xsl:value-of select="productStorage" /></td>
                            <td><xsl:value-of select="productColor" /></td>
                            <td><xsl:value-of select="qty" /></td>
                            <td><xsl:value-of select="productPrice" /></td>
                            <td><xsl:value-of select="productStatus" /></td>
                          </tr>
                        </xsl:for-each>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </xsl:for-each>
            </tbody>
          </table>
        </div>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
