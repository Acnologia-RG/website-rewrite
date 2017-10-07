<nav class="navbar navbar-inverse navbar-fixed-top animated fadeInDown" role="navigation" style="background: linear gradient(to right, rgb(255, 255, 255), rgb(242, 242, 242));">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="./index">
                <img class="circle-icon" src="./img/icon-small.png">
                <h1>HoroBot</h1>
                <a class="btn btn-default btn-invite hidden-xs" href="https://discordapp.com/oauth2/authorize?client_id=289381714885869568&scope=bot&permissions=372435975" target="_blank">
                    <b>Invite HoroBot</b>
                </a>
            </a>
            <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right" style="font-size: 14px">
                <li>
                    <a class="btn btn-default btn-invite hidden-xs" href="./index">
                        <i class="glyphicon glyphicon-home"></i>
                        <b>Home</b>
                    </a>
                </li>
                <li>
                    <a type="button" class="btn btn-default btn-invite hidden-xs dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="glyphicon glyphicon-info-sign"></i>
                        <b>Commands & Info</b>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="./about">
                                <i class="glyphicon glyphicon-question-sign"></i>
                                <b>About</b>
                            </a>
                            <a href="./commands">
                                <i class="glyphicon glyphicon-book"></i>
                                <b>Commands</b>
                            </a>
                            <a href="https://github.com/WinteryFox/HoroBot" target="_blank">
                                <i class="glyphicon glyphicon-console"></i>
                                <b>GitHub</b>
                            </a>
                            <a href="https://patreon.com/HoroBot" target="_blank">
                                <i class="glyphicon glyphicon-euro"></i>
                                <b>Patreon</b>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="https://discord.gg/MCUTSZz" class="btn btn-default btn-invite hidden-xs" target="_blank">
                        <i class="glyphicon glyphicon-comment"></i>
                        <b>Support</b>
                    </a>
                </li>
                <?php include __DIR__ . '/discord-user-access-token.php'; ?>
            </ul>
        </div>
    </div>
</nav>