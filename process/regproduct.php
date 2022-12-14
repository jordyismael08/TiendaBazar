<?php
    session_start();
    include '../library/configServer.php';
    include '../library/consulSQL.php';

    $codeProd=consultasSQL::clean_string($_POST['prod-codigo']);
    $nameProd=consultasSQL::clean_string($_POST['prod-name']);
    $cateProd=consultasSQL::clean_string($_POST['prod-categoria']);
    $priceProd=consultasSQL::clean_string($_POST['prod-price']);
    $modelProd=consultasSQL::clean_string($_POST['prod-model']);
    $marcaProd=consultasSQL::clean_string($_POST['prod-marca']);
    $stockProd=consultasSQL::clean_string($_POST['prod-stock']);
    $codePProd=consultasSQL::clean_string($_POST['prod-codigoP']);
    $estadoProd=consultasSQL::clean_string($_POST['prod-estado']);
    $adminProd=consultasSQL::clean_string($_POST['admin-name']);
    $descProd=consultasSQL::clean_string($_POST['prod-desc-price']);
    $imgName=$_FILES['img']['name'];
    $imgType=$_FILES['img']['type'];
    $imgSize=$_FILES['img']['size'];
    $imgMaxSize=5120;
 
    if($codeProd!="" && $nameProd!="" && $cateProd!="" && $priceProd!="" && $modelProd!="" && $marcaProd!="" && $stockProd!="" && $codePProd!=""){
        $verificar=  ejecutarSQL::consultar("SELECT * FROM producto WHERE CodigoProd='".$codeProd."'");
        $verificaltotal = mysqli_num_rows($verificar);
        if($verificaltotal<=0){
            if($imgType=="image/jpeg" || $imgType=="image/png"){
                if(($imgSize/1024)<=$imgMaxSize){
                    chmod('../assets/img-products/', 0777);
                    switch ($imgType) {
                      case 'image/jpeg':
                        $imgEx=".jpg";
                      break;
                      case 'image/png':
                        $imgEx=".png";
                      break;
                    }
                    $imgFinalName=$codeProd.$imgEx;
                    if(move_uploaded_file($_FILES['img']['tmp_name'],"../assets/img-products/".$imgFinalName)){
                        if(consultasSQL::InsertSQL("producto", "CodigoProd, NombreProd, CodigoCat, Precio, Descuento, Modelo, Marca, Stock, NITProveedor, Imagen, Nombre, Estado", "'$codeProd','$nameProd','$cateProd','$priceProd', '$descProd', '$modelProd','$marcaProd','$stockProd','$codePProd','$imgFinalName','$adminProd', '$estadoProd'")){
                            echo '<script>
                                swal({
                                  title: "Producto registrado",
                                  text: "El producto se a??adi?? a la tienda con ??xito",
                                  type: "success",
                                  showCancelButton: true,
                                  confirmButtonClass: "btn-danger",
                                  confirmButtonText: "Aceptar",
                                  cancelButtonText: "Cancelar",
                                  closeOnConfirm: false,
                                  closeOnCancel: false
                                  },
                                  function(isConfirm) {
                                  if (isConfirm) {
                                    location.reload();
                                  } else {
                                    location.reload();
                                  }
                                });
                            </script>';
                        }else{
                            echo '<script>swal("ERROR", "Ocurri?? un error inesperado, por favor intente nuevamente", "error");</script>';
                        }   
                    }else{
                        echo '<script>swal("ERROR", "Ha ocurrido un error al cargar la imagen", "error");</script>';
                    }  
                }else{
                    echo '<script>swal("ERROR", "Ha excedido el tama??o m??ximo de la imagen, tama??o m??ximo es de 5MB", "error");</script>';
                }
            }else{
                echo '<script>swal("ERROR", "El formato de la imagen del producto es invalido, solo se admiten archivos con la extensi??n .jpg y .png ", "error");</script>';
            }
        }else{
            echo '<script>swal("ERROR", "El c??digo de producto que acaba de ingresar ya est?? registrado en el sistema, por favor ingrese otro c??digo de producto distinto", "error");</script>';
        }
    }else {
        echo '<script>swal("ERROR", "Los campos no deben de estar vac??os, por favor verifique e intente nuevamente", "error");</script>';
    }