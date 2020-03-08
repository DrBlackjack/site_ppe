<!DOCTYPE html>
<html lang="fr">
  <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">
      Se connecter
    </button>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <?= \Config\Services::validation()->listErrors(); ?>
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title modal-name" id="staticBackdropLabel">Connexion</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <span class="d-none alert mb-3" id="res_message"></span>
          <div class="modal-body">
            <form action="javascript:void(0)" name="ajax_form" id="ajax_form" method="post" accept-charset="utf-8">
              <div class="container-float">
                <div class="form-group inscription-form" hidden="true">
                  <label for="prenom">Prénom</label>
                  <input type="text" class="form-control form-default" id="prenomInput" name="prenom">
                </div>
                <div class="form-group inscription-form" hidden="true">
                  <label for="nom">Nom</label>
                  <input type="text" class="form-control form-default" id="nomInput" name="nom">
                </div>
                <div class="form-group connexion-form">
                  <label for="email">Adresse mail</label>
                  <input type="email" class="form-control" id="emailInput" name="email" aria-describedby="emailHelp" required="required">
                </div>
                <div class="form-group connexion-form">
                  <label for="password">Mot de passe</label>
                  <input type="password" class="form-control" id="passwordInput" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Doit contenir au moins un chiffre et une lettre majuscule et minuscule, et au moins 8 caractères ou plus." name="password" required="required">
                </div>
              </div>
              <input type="hidden" class="form-mode" name="inscription-mode" value="0">
              <div class="modal-footer">
                <button type="button" class="btn btn-success btn-inscription">Inscription</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <input type="submit" class="btn btn-primary btn-submit" id="send_form" name="submit" value="Connexion">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
  <script>
    $(document).ready(function(){
      var inscription_mode = false;
      $('.inscription_mode').text("-1");
      $('.btn-inscription').click(function(){
        if (!inscription_mode){
          $('.modal-name').text('Inscription');
          $('.btn-inscription').text('Retour');
          $('.inscription-form').removeAttr('hidden');
          $('.btn-submit').attr('value','Inscription');
          $('.form-mode').attr('value','1');
          $('.inscription_form').text("");
          $('#prenomInput').attr('required','true');
          $('#nomInput').attr('required','true');
          inscription_mode = true;
        }
        else {
          $('.modal-name').text('Connexion');
          $('.btn-inscription').text('Inscription');
          $('.inscription-form').attr('hidden', 'true');
          $('.btn-submit').attr('value','Connexion');
          $('.form-mode').attr('value','0');
          $('.inscription_form').text("-1");
          $('#prenomInput').removeAttr('required');
          $('#nomInput').removeAttr('required');
          inscription_mode = false;
        }
      });
    });
    /*if ($("#ajax_form").length > 0) {
      $("#ajax_form").validate({
        rules: {
          name: {
            required: true,
          },
            email: {
              required: true,
              maxlength: 50,
              email: true,
            },
            message: {
            required: true,
          },
        },
          messages: {
            name: {
            required: "Please enter name",
          },
          email: {
            required: "Please enter valid email",
            email: "Please enter valid email",
            maxlength: "The email name should less than or equal to 50 characters",
          },
            message: {
              required: "Please enter message",
          },
        },
      })
    }*/
    //if( $('#ajax_form').valid() ) {
    $("#ajax_form").submit(function(form) {
      $('#send_form').html('Sending..');
      $.ajax({
        url: "<?php echo base_url('index.php/user/create') ?>",
        type: "POST",
        data: $('#ajax_form').serialize(),
        dataType: "json",
        success: function( response ) {
          console.log(response);
          console.log(response.success);
          if(response.success == true) {
            $('#send_form').html('Submit');
            $('#res_message').html(response.msg);
            $('#res_message').show();
            $('#res_message').removeClass('d-none');
            $('#res_message').removeClass('alert-warning').addClass('alert-success');
          }
          else {
            $('#res_message').html(response.msg);
            $('#res_message').show();
            $('#res_message').removeClass('d-none');
            $('#res_message').removeClass('alert-success').addClass('alert-warning');
          }
        }
      });
    });
  </script>
</html>
