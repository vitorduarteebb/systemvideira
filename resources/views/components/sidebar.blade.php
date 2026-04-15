@php
    $currentRoute = request()->route()->getName();
    $isDashboard = $currentRoute === 'dashboard';
    $isFunil = $currentRoute === 'crm.funil';
    $isClientes = $currentRoute === 'crm.clientes.index';
    $isEquipamentos = $currentRoute === 'crm.equipamentos.index';
    $isColaboradores = $currentRoute === 'crm.colaboradores.index';
    $isUsuarios = str_starts_with($currentRoute ?? '', 'crm.usuarios.');
    $isServicos = $currentRoute === 'crm.servicos.index';
    $isPrecificacao = $currentRoute === 'crm.precificacao.index';
    $isAgenda = $currentRoute === 'crm.agenda';
    $isRelatorios = str_starts_with($currentRoute ?? '', 'crm.relatorios.');
    $isQuestionarios = str_starts_with($currentRoute ?? '', 'crm.questionarios.');
    $isFinanceiroDashboard = $currentRoute === 'crm.financeiro.dashboard';
    $isContasPagar = str_starts_with($currentRoute ?? '', 'crm.financeiro.contas-pagar.');
    $isContasReceber = str_starts_with($currentRoute ?? '', 'crm.financeiro.contas-receber.');
    $isDre = $currentRoute === 'crm.financeiro.dre';
    $isEstoqueBaixo = str_starts_with($currentRoute ?? '', 'crm.financeiro.estoque.');
    $isColaboradorPortal = str_starts_with($currentRoute ?? '', 'crm.colaborador.');
    $uSidebar = auth()->user();
@endphp

@include('components.sidebar-styles')

<button type="button" class="mobile-menu-btn" id="videiraMobileMenuBtn" aria-controls="sidebar" aria-expanded="false" aria-label="Abrir ou fechar menu">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
        <line x1="3" y1="6" x2="21" y2="6"></line>
        <line x1="3" y1="12" x2="21" y2="12"></line>
        <line x1="3" y1="18" x2="21" y2="18"></line>
    </svg>
</button>
<div class="sidebar-backdrop" id="videiraSidebarBackdrop" aria-hidden="true"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo-icon">V</div>
        <div>
            <div class="logo-text">Videira</div>
            <div class="logo-subtitle">GESTÃO INTELIGENTE</div>
        </div>
    </div>
    <canvas id="particlesCanvas" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0.3; pointer-events: none; z-index: 0;"></canvas>
    
    <div class="sidebar-menu">
        @if($uSidebar && $uSidebar->isTecnico())
            <div class="menu-section">
                <div class="menu-section-title">MINHA ÁREA</div>
                <a href="{{ route('dashboard') }}" class="menu-item {{ $isDashboard ? 'active' : '' }}">
                    <div class="menu-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                    </div>
                    <span>Início</span>
                </a>
                <a href="{{ route('crm.agenda') }}" class="menu-item {{ $isAgenda ? 'active' : '' }}">
                    <div class="menu-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <span>Agenda</span>
                </a>
            </div>
            @if(isset($servicosExecucaoMenu) && $servicosExecucaoMenu->isNotEmpty())
                <div class="menu-section">
                    <div class="menu-section-title">SERVIÇOS EM EXECUÇÃO</div>
                    @foreach($servicosExecucaoMenu as $svcMenu)
                        <a href="{{ route('crm.colaborador.execucao', $svcMenu) }}" class="menu-item {{ ($isColaboradorPortal && (int) optional(request()->route('servico'))->id === (int) $svcMenu->id) ? 'active' : '' }}">
                            <div class="menu-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                                </svg>
                            </div>
                            <span style="line-height:1.25;">O.S {{ $svcMenu->numero_os ?? $svcMenu->id }}<br><small style="opacity:.75;font-size:11px;">{{ \Illuminate\Support\Str::limit($svcMenu->cliente->nome ?? 'Cliente', 22) }}</small></span>
                        </a>
                    @endforeach
                </div>
            @endif
            <div class="menu-section">
                <div class="menu-section-title">CONTA</div>
                <form method="POST" action="{{ route('logout') }}" style="display: contents;">
                    @csrf
                    <button type="submit" class="menu-item" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; color: rgba(255, 255, 255, 0.65);">
                        <div class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                        </div>
                        <span>SAIR</span>
                    </button>
                </form>
            </div>
        @else
        <div class="menu-section">
            <div class="menu-section-title">PRINCIPAL</div>
            <a href="{{ route('dashboard') }}" class="menu-item {{ $isDashboard ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                </div>
                <span>Visão Geral</span>
            </a>
            <a href="{{ route('crm.funil') }}" class="menu-item {{ $isFunil ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                </div>
                <span>Funil CRM</span>
            </a>
            <a href="{{ route('crm.precificacao.index') }}" class="menu-item {{ $isPrecificacao ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="4" y="4" width="16" height="16" rx="2"></rect>
                        <line x1="8" y1="8" x2="16" y2="8"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                        <line x1="8" y1="16" x2="12" y2="16"></line>
                    </svg>
                </div>
                <span>Precificador</span>
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">OPERAÇÃO</div>
            <a href="{{ route('crm.servicos.index') }}" class="menu-item {{ $isServicos ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                    </svg>
                </div>
                <span>Serviços</span>
            </a>
            <a href="{{ route('crm.agenda') }}" class="menu-item {{ $isAgenda ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
                <span>Agenda</span>
            </a>
            <a href="{{ route('crm.relatorios.index') }}" class="menu-item {{ $isRelatorios ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <span>Relatórios</span>
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">RECURSOS</div>
            <a href="{{ route('crm.questionarios.index') }}" class="menu-item {{ $isQuestionarios ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 11h6"></path>
                        <path d="M9 15h6"></path>
                        <path d="M10 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9l-6-6z"></path>
                    </svg>
                </div>
                <span>Questionários</span>
            </a>
            <a href="{{ route('crm.clientes.index') }}" class="menu-item {{ $isClientes ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </div>
                <span>Clientes</span>
            </a>
            <a href="{{ route('crm.equipamentos.index') }}" class="menu-item {{ $isEquipamentos ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="9" y1="3" x2="9" y2="21"></line>
                    </svg>
                </div>
                <span>Equipamentos</span>
            </a>
            <a href="{{ route('crm.colaboradores.index') }}" class="menu-item {{ $isColaboradores ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <span>Colaboradores</span>
            </a>
            <a href="{{ route('crm.usuarios.index') }}" class="menu-item {{ $isUsuarios ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <span>Usuários</span>
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">ADMINISTRAÇÃO</div>
            <a href="{{ route('crm.financeiro.dashboard') }}" class="menu-item {{ $isFinanceiroDashboard ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 3v18h18"></path>
                        <path d="M19 9l-5 5-4-4-3 3"></path>
                    </svg>
                </div>
                <span>Financeiro</span>
            </a>
            <a href="{{ route('crm.financeiro.contas-receber.index') }}" class="menu-item {{ $isContasReceber ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                        <line x1="2" y1="10" x2="22" y2="10"></line>
                    </svg>
                </div>
                <span>Contas a Receber</span>
            </a>
            <a href="{{ route('crm.financeiro.contas-pagar.index') }}" class="menu-item {{ $isContasPagar ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                </div>
                <span>Contas a Pagar</span>
            </a>
            <a href="{{ route('crm.financeiro.dre') }}" class="menu-item {{ $isDre ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16v16H4z"></path>
                        <path d="M8 9h8"></path>
                        <path d="M8 13h8"></path>
                    </svg>
                </div>
                <span>DRE</span>
            </a>
            <a href="{{ route('crm.financeiro.estoque.baixo') }}" class="menu-item {{ $isEstoqueBaixo ? 'active' : '' }}">
                <div class="menu-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                </div>
                <span>Estoque baixo</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" style="display: contents;">
                @csrf
                <button type="submit" class="menu-item" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; color: rgba(255, 255, 255, 0.65);">
                    <div class="menu-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </div>
                    <span>SAIR</span>
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

<script>
    // Partículas animadas na sidebar
    function initParticles() {
        const canvas = document.getElementById('particlesCanvas');
        if (!canvas) return;
        
        const ctx = canvas.getContext('2d');
        const sidebar = document.getElementById('sidebar');
        
        if (!sidebar) return;
        
        canvas.width = sidebar.offsetWidth;
        canvas.height = sidebar.offsetHeight;
        
        const particles = [];
        const particleCount = 30;
        
        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 1;
                this.speedX = (Math.random() - 0.5) * 0.5;
                this.speedY = (Math.random() - 0.5) * 0.5;
                this.opacity = Math.random() * 0.5 + 0.2;
            }
            
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                
                if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
                if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
            }
            
            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(135, 206, 235, ${this.opacity})`;
                ctx.fill();
            }
        }
        
        for (let i = 0; i < particleCount; i++) {
            particles.push(new Particle());
        }
        
        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            particles.forEach(particle => {
                particle.update();
                particle.draw();
            });
            
            // Conectar partículas próximas
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    
                    if (distance < 100) {
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.strokeStyle = `rgba(135, 206, 235, ${0.1 * (1 - distance / 100)})`;
                        ctx.lineWidth = 1;
                        ctx.stroke();
                    }
                }
            }
            
            requestAnimationFrame(animate);
        }
        
        animate();
        
        window.addEventListener('resize', () => {
            canvas.width = sidebar.offsetWidth;
            canvas.height = sidebar.offsetHeight;
        });
    }
    
    // Inicializar partículas quando a página carregar
    document.addEventListener('DOMContentLoaded', initParticles);

    (function () {
        function initMobileMenu() {
            const btn = document.getElementById('videiraMobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const bd = document.getElementById('videiraSidebarBackdrop');
            if (!btn || !sidebar || !bd) return;

            function open() {
                sidebar.classList.add('sidebar-open');
                bd.classList.add('is-visible');
                document.body.style.overflow = 'hidden';
                btn.setAttribute('aria-expanded', 'true');
                bd.setAttribute('aria-hidden', 'false');
            }

            function close() {
                sidebar.classList.remove('sidebar-open');
                bd.classList.remove('is-visible');
                document.body.style.overflow = '';
                btn.setAttribute('aria-expanded', 'false');
                bd.setAttribute('aria-hidden', 'true');
            }

            function toggle() {
                if (sidebar.classList.contains('sidebar-open')) close();
                else open();
            }

            btn.addEventListener('click', toggle);
            bd.addEventListener('click', close);
            sidebar.querySelectorAll('.menu-item').forEach(function (el) {
                el.addEventListener('click', function () {
                    if (window.matchMedia('(max-width: 1024px)').matches) close();
                });
            });
            window.addEventListener('resize', function () {
                if (window.innerWidth > 1024) close();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && sidebar.classList.contains('sidebar-open')) close();
            });
        }

        document.addEventListener('DOMContentLoaded', initMobileMenu);
    })();
</script>
