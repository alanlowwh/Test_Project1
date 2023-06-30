<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:laravel="http://laravel.com/ns">

    <xsl:param name="csrf_token" />

    <xsl:template match="/">
        <html>
            <head>
                <meta name="viewport" content="width=device-width, initial-scale=1" />
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" />
                <style>
                    body {
                        margin-top: 20px;
                        margin-left: 220px;
                        margin-right: 220px;
                    }

                    table {
                        width: 80%;
                        margin: 0 auto;
                    }

                    .btn-info {
                        color: white;
                        background-color: #0dcaf0;
                        border-color: #0dcaf0;
                    }

                    .btn-container {
                        display: flex;
                        justify-content: flex-start;
                    }

                    .btn-container a,
                    .btn-container button {
                        margin-right: 10px;
                    }

                    /* Style for back button */
                    .back-button {
                        position: absolute;
                        top: 20px;
                        right: 20px;
                        margin-right: 200px;
                    }
                </style>
                <script>
                    function confirmDelete() {
                        return confirm("Are you sure you want to delete this account?");
                    }
                </script>
            </head>
            <body>
                <div>
                    <h2>Customer List</h2>

                    <!-- Check for success message from controller -->
                    <xsl:if test="/*/success_message">
                        <div class="alert alert-success">
                            <p>
                                <xsl:value-of select="/*/success_message"/>
                            </p>
                        </div>
                    </xsl:if>

                    <table class="table table-bordered">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th width="280px">Action</th>
                        </tr>
                        <xsl:for-each select="users/user">
                            <tr>
                                <td>
                                    <xsl:value-of select="id"/>
                                </td>
                                <td>
                                    <xsl:value-of select="username"/>
                                </td>
                                <td>
                                    <xsl:value-of select="email"/>
                                </td>
                                <td>
                                    <div class="btn-container">
                                        <form action="{concat('users/', id)}" method="POST">
                                            <a href="{concat('users/', id)}" class="btn btn-primary">
                                                <xsl:text>Show</xsl:text>
                                            </a>
                                            <a href="{concat('users/', id, '/edit')}" class="btn btn-primary">
                                                <xsl:text>Edit</xsl:text>
                                            </a>
                                            <input type="hidden" name="_token" value="{$csrf_token}" />
                                            <input type="hidden" name="_method" value="DELETE" />
                                            <button type="submit" class="btn btn-danger" onclick="return confirmDelete()">
                                                <xsl:text>Delete</xsl:text>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </xsl:for-each>
                    </table>

                    <a href="http://127.0.0.1:8000/" class="btn btn-info back-button">Back</a>

                    <xsl:value-of select="users/user/links"/>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
