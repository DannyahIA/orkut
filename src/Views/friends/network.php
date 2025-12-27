<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>orkut - rede de amigos</title>
    <link rel="stylesheet" href="/css/style.css">
    <!-- Use vis.js for the graph -->
    <script type="text/javascript" src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
    <style>
        #network {
            height: 500px;
            border: 1px solid #ddd;
            background: #f9f9f9;
        }
    </style>
</head>

<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar-left">
            <div class="menu-links" style="margin-top: 0;">
                <a href="/">🔙 voltar para home</a>
                <a href="/profile">👤 meu perfil</a>
                <a href="/friends">👥 meus amigos</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="section-header">
                minha rede (the donut)
            </div>

            <div class="content-box">
                <div id="network"></div>
                <p style="font-size: 10px; color: #666; margin-top: 5px;">Visualizando suas conexões diretas.</p>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // Fetch data
        fetch('/friends/network-data')
            .then(response => response.json())
            .then(data => {
                var nodes = new vis.DataSet(data.nodes);
                var edges = new vis.DataSet(data.edges);

                var container = document.getElementById('network');
                var data = {
                    nodes: nodes,
                    edges: edges
                };
                var options = {
                    nodes: {
                        shape: 'dot',
                        size: 20,
                        font: {
                            size: 12,
                            color: '#000'
                        },
                        borderWidth: 2,
                        shadow: true,
                        color: {
                            background: '#B0235F',
                            border: '#ffffff',
                            highlight: {
                                background: '#FF4081',
                                border: '#ffffff'
                            }
                        }
                    },
                    edges: {
                        width: 2,
                        shadow: true,
                        color: { inherit: 'from' },
                        smooth: {
                            type: 'continuous'
                        }
                    },
                    physics: {
                        stabilization: false,
                        barnesHut: {
                            gravitationalConstant: -3000,
                            springConstant: 0.04,
                            springLength: 95
                        }
                    },
                    interaction: {
                        navigationButtons: true,
                        keyboard: true,
                        zoomView: true
                    }
                };
                var network = new vis.Network(container, data, options);
            });
    </script>
</body>

</html>