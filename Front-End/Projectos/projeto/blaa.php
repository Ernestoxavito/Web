<?php
// Incluindo a conexão com o banco de dados
include('conexao.php');

// Função para buscar dados de doações do banco, por exemplo
function getDoacoes() {
    global $conexao;
    $query = "SELECT tipo_sanguineo, COUNT(*) AS quantidade FROM doacoes GROUP BY tipo_sanguineo";
    $result = $conexao->query($query);
    return $result;
}

// Função para buscar agendamentos, por exemplo
function getAgendamentos() {
    global $conexao;
    $query = "SELECT MONTH(data_agendamento) AS mes, COUNT(*) AS total FROM agendamentos GROUP BY MONTH(data_agendamento)";
    $result = $conexao->query($query);
    return $result;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Time do Sangue</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="asd.css">
</head>
<body>
    <div class="container">
        <!-- Barra lateral -->
        <aside class="sidebar">
            <h2>Gestão de <span>Sangue</span></h2>
            <nav>
                <ul>
                    <li class="active"><strong>CADASTROS</strong></li>
                    <li>Doadores</li>
                    <li>Enfermeiro</li>
                    <li>Agendamentos</li>
                    <li><strong>NOTIFICAÇÕES</strong></li>
                    <li>Campanha</li>
                    <li><strong>RELATÓRIOS</strong></li>
                    <li>Dashboard</li>
                    <li>Demanda</li>
                    <li>Doador</li>
                    <li>Listagem</li>
                </ul>
            </nav>
        </aside>

        <!-- Conteúdo principal -->
        <main class="dashboard">
            <header>
                <h1>Dashboard</h1>
                <div class="profile">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-count">3</span>
                    </div>
                    <div class="messages">
                        <i class="fas fa-envelope"></i>
                        <span class="message-count">5</span>
                    </div>
                    <div class="user-profile">
                        <img src="https://via.placeholder.com/40" alt="User">
                        <span>Usuário</span>
                    </div>
                </div>
            </header>

            <section class="cards">
                <div class="card">Agendamentos</div>
                <div class="card">Agendamentos confirmados</div>
                <div class="card">Doadores atendidos</div>
                <div class="card">Doadores que faltaram</div>
                <div class="card">Doadores reprovados</div>
                <div class="card">Agendamentos cancelados</div>
            </section>

            <!-- Gestão de Estoque de Sangue -->
            <section class="stock-management">
                <h3>Gestão de Estoque de Sangue</h3>
                <div class="stock-container">
                    <div class="stock-item">
                        <h4>Quantidade de Bolsas Disponíveis</h4>
                        <canvas id="stockChart"></canvas>
                    </div>
                    <div class="alerts">
                        <h4>Alertas de Estoque</h4>
                        <ul>
                            <li class="alert alert-low">A+ - Estoque baixo!</li>
                            <li class="alert alert-expiring">O+ - Bolsas prestes a vencer!</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Relatórios Detalhados -->
            <section class="reports">
                <h3>Relatórios Detalhados</h3>
                <p>Aqui você pode acessar relatórios detalhados para análise interna.</p>
            </section>

            <!-- Solicitação Interna de Bolsas -->
            <section class="internal-requests">
                <h3>Solicitação Interna de Bolsas</h3>
                <form id="blood-request-form">
                    <label for="hospital">Hospital/Setor:</label>
                    <input type="text" id="hospital" name="hospital" required>
                    <label for="blood-type">Tipo de Sangue:</label>
                    <select id="blood-type" name="blood-type">
                        <option value="A+">A+</option>
                        <option value="O+">O+</option>
                        <option value="B+">B+</option>
                        <option value="AB+">AB+</option>
                    </select>
                    <label for="quantity">Quantidade:</label>
                    <input type="number" id="quantity" name="quantity" required min="1">
                    <button type="submit">Solicitar</button>
                </form>
            </section>

            <section class="charts">
                <div class="chart-container">
                    <h3>Doações</h3>
                    <canvas id="doacoesChart"></canvas>
                </div>
                <div class="chart-container">
                    <h3>Tipos Sanguíneos</h3>
                    <canvas id="sangueChart"></canvas>
                </div>
                <div class="chart-container">
                    <h3>Agendamentos no período</h3>
                    <canvas id="agendamentosChart"></canvas>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Gestão de Estoque de Sangue
        const ctxStock = document.getElementById('stockChart').getContext('2d');
        new Chart(ctxStock, {
            type: 'bar',
            data: {
                labels: ['A+', 'O+', 'B+', 'AB+', 'A-', 'O-', 'B-', 'AB-'],
                datasets: [{
                    label: 'Bolsas Disponíveis',
                    data: [150, 200, 100, 50, 30, 40, 20, 10],
                    backgroundColor: ['#FF5722', '#E91E63', '#9C27B0', '#3F51B5', '#009688', '#4CAF50', '#FFC107', '#795548']
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Solicitação Interna de Bolsas
        document.getElementById('blood-request-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const hospital = document.getElementById('hospital').value;
            const bloodType = document.getElementById('blood-type').value;
            const quantity = document.getElementById('quantity').value;
            alert(`Pedido realizado para ${hospital} - Tipo de Sangue: ${bloodType}, Quantidade: ${quantity}`);
        });

        // Configuração dos gráficos (já existentes)
        const ctx1 = document.getElementById('doacoesChart').getContext('2d');
        new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['Novos', 'Recorrentes'],
                datasets: [{
                    data: [70, 30],
                    backgroundColor: ['#1976D2', '#D32F2F']
                }]
            },
            options: {
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });

        const ctx2 = document.getElementById('sangueChart').getContext('2d');
        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['A-', 'A+', 'B-', 'B+', 'AB-', 'AB+', 'O-', 'O+'],
                datasets: [{
                    data: [5, 15, 10, 10, 5, 10, 5, 40],
                    backgroundColor: ['#FF5722', '#E91E63', '#9C27B0', '#3F51B5', '#009688', '#4CAF50', '#FFC107', '#795548']
                }]
            },
            options: {
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });

        const ctx3 = document.getElementById('agendamentosChart').getContext('2d');
        new Chart(ctx3, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Agendamentos',
                    data: [30, 50, 40, 60, 80, 100],
                    borderColor: '#0288D1',
                    fill: false
                }]
            },
            options: {
                animation: {
                    duration: 1500
                }
            }
        });
    </script>
</body>
</html>