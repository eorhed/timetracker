<?php require_once "app/views/header.php"; ?>
<?php 
    require_once "app/views/header.php";
    
    if (!Session::manageSession() || !Session::isLogged() || !Session::isAdmin())
        header("Location: login.php");
?>

    
    <main>
        <section id="usuarios">
            <div class="container-fluid">
                <h1>Usuarios</h1>
                <form action="usuarios.php">
                    <div class="tabla">
                        <table>
                            <thead>
                                <tr>
                                    <td width="20">#</td>
                                    <td>USUARIO</td>
                                    <td align="center" width="200">FECHA CREACI&Oacute;N</td>
                                    <td align="center" width="200">FECHA FIN</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="20">1</td>
                                    <td>Usuario 1</td>
                                    <td align="center" width="200">21/10/2021</td>
                                    <td align="center" width="200">-</td>
                                </tr>
                                <tr>
                                    <td width="20">2</td>
                                    <td>Usuario 2</td>
                                    <td align="center" width="200">21/10/2021</td>
                                    <td align="center" width="200">-</td>
                                </tr>
                                <tr>
                                    <td width="20">3</td>
                                    <td>Usuario 3</td>
                                    <td align="center" width="200">21/10/2021</td>
                                    <td align="center" width="200">-</td>
                                </tr>
                                <tr>
                                    <td width="20">4</td>
                                    <td>Usuario 4</td>
                                    <td align="center" width="200">21/10/2021</td>
                                    <td align="center" width="200">-</td>
                                </tr>
                                <tr>
                                    <td width="20">5</td>
                                    <td>Usuario 5</td>
                                    <td align="center" width="200">21/10/2021</td>
                                    <td align="center" width="200">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </section>
    </main>

<?php require_once "app/views/footer.php"; ?>