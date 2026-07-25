<?php
 session_start();
 
 include 'conexao.php';

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="shortcut icon" href="src/assets/img/icon/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="src/assets/css/bootstrap-grid.min.css" />
        <link rel="stylesheet" href="src/assets/css/schedulingStyle.css" />
        <link rel="stylesheet" href="src/assets/css/main.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
        <title>Sangue Solidário | main</title>
    </head>
    <body>
        <div class="overlay toggle-menu"></div>

        <!-- HEADER -->
        <header>
            <div class="p-md-0 container-fluid">
                <div class="p-md-0 container">
                    <div class="navBar">
                        <div>
                            <img src="src/assets/img/icon/Logo.svg" alt="Banner" class="logo" />
                        </div>
                        <nav class="nav-container">
                            <a href="#home" class="hv-home text-color-light">Home</a>
                            <a href="#sevicos" class="hv-servico text-color-light">Serviços</a>
                            <a href="#informacao" class="hv-informacao text-color-light">Informações</a>
                            <a href="#contato" class="hv-contato text-color-light">Contato</a>
                            <div class="bg-hover"></div>
                        </nav>

                        <div class="btn-container">
    <?php if (isset($_SESSION['usuario_nome'])): ?>
        <a href="perfil.php">
            <button class="btn-perfil"><?= htmlspecialchars($_SESSION['usuario_nome']) ?></button>
        </a>
        <a href="Logout.php">
            <button class="btn-login">Logout</button>
        </a>
    <?php else: ?>
        <button class="btn-cadastro" onclick="window.location.href='cadastro.php'">Cadastre-se</button>
        <button class="btn-login" onclick="window.location.href='login.php'">Login</button>
    <?php endif; ?>
</div>
                           
                        </div>
                    </div>
                </div>            
            </div>
        </header>
        <!-- FIM HEADER -->

        <!-- MENU MOBILE -->
        <button class="btn-menu-mob toggle-menu">
            <ion-icon name="menu-outline"></ion-icon>
        </button>

        <nav class="navBar-mob menu-is-close">
            <a href="Index.html" class="hv-home text-color-light">Home</a>
            <a href="sevicos" class="hv-servico text-color-light">Serviços</a>
            <a href="#informacao" class="hv-informacao text-color-light">Informações</a>
            <a href="#contato" class="hv-contato text-color-light">Contato</a>
            <div class="bg-hover"></div>

            
            <div class="btn-container">
    <?php if (isset($_SESSION['usuario_nome'])): ?>
        <a href="perfil.php">
            <button class="btn-perfil"><?= htmlspecialchars($_SESSION['usuario_nome']) ?></button>
        </a>
        <a href="Logout.php">
            <button class="btn-login">Logout</button>
        </a>
    <?php else: ?>
        <button class="btn-cadastro" onclick="window.location.href='cadastro.php'">Cadastre-se</button>
        <button class="btn-login" onclick="window.location.href='login.php'">Login</button>
    <?php endif; ?>
</div>
            
        </nav>
        <!-- FIM MENU MOBILE -->

        <!-- HOME -->
        <section id="home">
            <div class="banner">
                <div class="p-md-0 container">
                    <div class="p-md-0 col-md-7">
                        <div class="box-text">
                            <h3 class="title-banner text-color-light">faça a sua doação!</h3>
                            <p class="text-banner text-color-light">Faça sua parte, agende aqui sua doação. doar é um ato de amor e salva vidas, a cada doação você salva ate quatro pessoas. o ato de doar nao traz nenhum maleficio a sua saúde pois o seu corpo se auto organiza positivamente.</p>

                            <a href="agendamento.php">
                                <button class="btn-banner">
                                    <span>Agendar</span>
                                </button>
                            </a>
                        </div>
                        <div class="scroll-Down">
                            <img src="src/assets/img/icon/scroll-down.svg" alt="Rolar para baixo" />
                            <p class="m-0 text-color-light">Mais informações a baixo</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- FIM HOME -->

        <!-- SERVIÇOS -->
        <section id="servicos">
            <div class="p-md-0 container">
                <div class="container-servc">
                    <div class="text-servico">
                        <div class="line"></div>
                        <h3 class="title">Veja os <span class="text-color-red"> serviços </span> que podemos oferecer!</h3>
                        <p class="text">Acompanhe os nossos serviços de forma rápida e pratica para ajudar quem necessita.</p>
                    </div>

                    <div class="cards-servico">
                        
                            <div class="box-cs1">
                                <div class="p-3 col-sm-6 first-card">
                                    <div class="box-interno">
                                        <img src="src/assets/img/icon/icon-agendamento.svg" alt="icone de Calendario" class="icon-card" />
                                        <a href="src/pages/login.html">
                                          <a href="Agendamento.php"> <h5 class="text-color-light">Agendamento.php</h5></a>  

                                            <p class="text-color-light">Agende aqui sua doação, sem problemas e com as facilidades que dispomos no site.</p>
                                        </a>
                                    </div>
                            </div>
                        

                            <div class="p-3 col-sm-6 card">
                                <div class="box-interno">
                                    <img src="src/assets/img/icon/icon-info.svg" alt="icone de Informação" class="icon-card" />
                                    <h5 class="text-color-dark">Informe-se</h5>
                                     <a href="#" class="text-color-dark" onclick="abrirModalInformese(); return false;">Informa-se</a> 

                                    <p class="text-color-dark">Tem duvidas? fale conosco através do e-mail ou telefoneque que ajudamos você.</p>
                                </div>
                            </div>
                        </div>

                        <div class="box-cs2">
                            <div class="p-3 col-sm-6 card">
                                <div class="box-interno">
                                    <img src="src/assets/img/icon/icon-bs.svg" alt="Icone do Banco de Sangue" class="icon-card" />
                                   
                                    <a href="banco_sangue.php"> <h5 class="text-color-dark">Banco de Sangue</h5> </a>
                                    

                                    <p class="text-color-dark">Veja na seção banco de sangue o que precisamos para ser doado e o que ofertamos aos receptores.</p>
                                </div>
                            </div>

                            <div class="p-3 col-sm-6 card">
                                <div class="box-interno">
                                    <img src="src/assets/img/icon/icon-feedback.svg" alt="Icone de Feedback" class="icon-card" />
                                    <h5 class="text-color-dark">Envie seu feedback</h5>
                                     <a href="Feedback.php"> >Feedback.php</h5></a> 

                                    <p class="text-color-dark">Gostou dos nossos serviços? tem alguma sugestão ou reclamação por favor nos envie sua opinião.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- FIM SERVIÇOS -->

        <!-- VIDEO -->
        <section class="banner-video">
            <div class="p-md-0 container">
                <div class="box-bannerVid">
                    <div class="box-video-Info">
                            <video id="video" class="box-video" src="src/assets/videos/video.mp4" controls autoplay loop muted></video>

                        <div class="text-video">
                            <div class="line-v"></div>
                            <h5 class="title">Assista nosso <span class="text-color-red">vídeo!</span></h5>
                            <p class="text">Quer entender mais sobre esse mundo da doação? Assista nosso vídeo para ficar por dentro das novidades.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- FIM VIDEO -->

        <!-- INFORMAÇÕES -->
        <section id="informacao" class="info-sangue">
            <div class="container">
                <div class="box-textS">
                    <div class="box-info">
                        <div class="lineIn1 bg-color-red"></div>
                        <h2 class="title text-color-red">Por que doar sangue ?</h2>
                        <p class="text text-color-dark espace-info">A doação é um ato altruísta e de solidariedade, a doação de sangue é um ato que salva vidas. Uma única doação pode ajudar a salvar até quatro vidas. Esse é um gesto de amor que pode gerar muitos sorrisos.</p>

                        <h2 class="title text-color-red">Sou receptor, como eu faço?</h2>
                        <p class="text text-color-dark espace-info">cadastre-se e solicite a sua doação, se for um receptor periódico favor especificar sua condição e tipo sanguíneo no cadastro, para que possamos encontrar doadores.</p>

                        <h2 class="title text-color-red">Como fazer a doação?</h2>
                        <p class="text text-color-dark">Se cadastrar com dias e horários definidos no site que comunicara o hemocentro de sua região para facilitar o processo.</p>
                        <div class="lineIn2 bg-color-red"></div>
                    </div>
                    <div class="box-svg-info">
                        <img src="src/assets/img/svg-home.svg" alt="Ilustração de Proficionais de Saúde" />
                    </div>
                </div>
            </div>
        </section>
        <!-- FIM INFORMAÇÕES -->
        <!-- CONTATO -->
        <section id="contato" class="bg-contato">
            <div class="container">
                <div class="box-contato">
                    <h6 class="title-form text-color-dark">Contate-nos</h6>
                    <form class="modal-form" id="form-contato">
                        <div class="box-info-contato">
                            <div class="box-form">
                                <label for="nome"></label>
                                <input type="text" id="nome" name="Nome" placeholder="Nome" required />

                                <label for="email"></label>
                                <input type="text" id="email" name="Email" placeholder="E-mail" required />
                            </div>

                            <div class="box-form">
                                <label for="telefone"></label>
                                <input type="tel" id="telefone" name="Telefone" placeholder="Telefone" required />
                            </div>
                        </div>
                        <div class="box-form">
                            <label for="msg"></label>
                            <textarea id="msg" name="msg" placeholder="Digite uma mensagem (Opicional)"></textarea>
                        </div>
                        <button type="submit" class="btn-contato bg-color-red text-color-light">Enviar</button>
                    </form>
                    <div id="mensagem-contato" style="margin-top:10px;text-align:center;"></div>
                </div>

                <div class="box-detalhe">
                    <h6 class="title-form text-color-dark">Detalhes</h6>
                    <p class="text">Contate-nos através do e-mail ou número.</p>
                    <div class="box-det-contato">
                        <div class="email-det">
                            <img src="src/assets/img/icon/email.svg"  alt="Icone de Email" />
                            <p class="pl-1 m-0 text-color-dark">Banco.de.sangue@gmail.com</p>
                        </div>
                        <div class="telefone-det">
                            <img src="src/assets/img/icon/telephone.svg" alt="Icone de Email" />
                            <p class="pl-1 m-0 text-color-dark">(+244) 000-000-000</p>
                        </div>
                    </div>
                    <div class="box-feedback">
                     <form id="form-feedback">
                        <label for="feedback"></label>
                            <input id="feedback" name="feedback" placeholder="Escreva seu feedback..." />
                              <button type="submit" class="btn-feed bg-color-red text-color-light">Enviar</button>
                                 </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- FIM CONTATO -->
  
        <!-- FOOTER -->
        <footer>
            <div class="bg-footer bg-color-red">
                <div class="p-md-0 container">
                    <div class="box-footer">
                        <div class="footer-sobre">
                            <h6 class="title-footer text-color-light">Sobre</h6>
                            <p class="text-footer text-color-light">Nossa empresa é uma plataforma inovadora de agendamento de doação de sangue online, dedicada a facilitar e promover o ato nobre de doar sangue.</p>
                        </div>

                        <div class="footer-servicos">
                            <h6 class="title-footer text-color-light">Serviços</h6>
                            <a href="#">Agendamento</a>
                            <a href="#">Informações</a>
                            <a href="#">Como doar?</a>
                        </div>

                        <div class="seguir">
                            <h6 class="title-footer text-color-light">Redes Sociais</h6>
                            <div class="redes-sociais">
                                <img src="src/assets/img/icon/instagram.svg" alt="Instagram" />
                                <img src="src/assets/img/icon/facebook.svg" alt="Facebook" />
                                <img src="src/assets/img/icon/twitter.svg" alt="Twitter" />
                                <img src="src/assets/img/icon/linkedin.svg" alt="Linkedin" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-serguir-mob">
                <div class="m-0 seguir-mob">
                    <div class="redes-sociais-mob">
                        <img src="src/assets/img/icon/instagram.svg" alt="Instagram" />
                        <img src="src/assets/img/icon/facebook.svg" alt="Facebook" />
                        <img src="src/assets/img/icon/twitter.svg" alt="Twitter" />
                        <img src="src/assets/img/icon/linkedin.svg" alt="Linkedin" />
                    </div>
                </div>
            </div>
        </footer>
        <!-- FIM FOOTER -->

        <script src="/src/assets/js/main.js"></script>
       
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
     
        <script>
document.getElementById('form-contato').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const dados = new FormData(form);

    fetch('enviar_contato.php', {
        method: 'POST',
        body: dados
    })
    .then(res => res.json())
    .then(resp => {
        document.getElementById('mensagem-contato').innerHTML = 
            resp.status === 'ok' 
            ? '<span style="color:green;">' + resp.mensagem + '</span>'
            : '<span style="color:red;">' + resp.mensagem + '</span>';
        if(resp.status === 'ok') form.reset();
    })
    .catch(() => {
        document.getElementById('mensagem-contato').innerHTML = 
            '<span style="color:red;">Erro inesperado ao enviar mensagem.</span>';
    });
});
</script>
<!-- Modal Informe-se -->
<div id="modal-informese" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:2000;">
  <div style="background:#fff; padding:24px; border-radius:8px; min-width:320px; max-width:90vw; position:relative;">
    <button onclick="fecharModalInformese()" style="position:absolute;top:8px;right:8px;font-size:18px;background:none;border:none;">&times;</button>
    <h4 class="title text-color-red">Tire sua dúvida agora!</h4>
    <form id="form-informese-modal">
        <input type="text" name="nome" placeholder="Seu nome" required class="form-control mb-2">
        <input type="email" name="email" placeholder="Seu e-mail" required class="form-control mb-2">
        <textarea name="duvida" placeholder="Digite sua dúvida aqui..." required class="form-control mb-2"></textarea>
        <button type="submit" class="btn btn-danger">Enviar dúvida</button>
    </form>
    <div id="mensagem-informese-modal" style="margin-top:10px;text-align:center;"></div>
  </div>
</div>
<script>
function abrirModalInformese() {
    document.getElementById('modal-informese').style.display = 'flex';
}
function fecharModalInformese() {
    document.getElementById('modal-informese').style.display = 'none';
    document.getElementById('form-informese-modal').reset();
    document.getElementById('mensagem-informese-modal').innerHTML = '';
}

// Envio em tempo real
document.getElementById('form-informese-modal').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const dados = new FormData(form);

    fetch('enviar_informese.php', {
        method: 'POST',
        body: dados
    })
    .then(res => res.json())
    .then(resp => {
        document.getElementById('mensagem-informese-modal').innerHTML = 
            resp.status === 'ok' 
            ? '<span style="color:green;">' + resp.mensagem + '</span>'
            : '<span style="color:red;">' + resp.mensagem + '</span>';
        if(resp.status === 'ok') form.reset();
    })
    .catch(() => {
        document.getElementById('mensagem-informese-modal').innerHTML = 
            '<span style="color:red;">Erro inesperado ao enviar dúvida.</span>';
    });
});
</script>
    </body>
</html>