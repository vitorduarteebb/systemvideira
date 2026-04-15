<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Videira Engenharia e Serviços — soluções em engenharia, HVAC, utilidades, PMOC, facilities e eficiência energética para operações industriais exigentes.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Videira Engenharia e Serviços</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Syne:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/landing-videira.css', 'resources/js/landing-videira.js'])
</head>
<body class="landing-videira">
@php
    $wa = preg_replace('/\D/', '', config('videira.whatsapp', '5511999999999'));
    $mapsUrl = config('videira.maps_embed_url');
    $address = config('videira.address');
@endphp

<a href="#conteudo-principal" class="visually-hidden">Ir para o conteúdo principal</a>

<header class="site-header" id="topo" role="banner">
    <div class="site-header__inner">
        <a href="{{ route('home') }}" class="brand" aria-label="Videira Engenharia e Serviços — início">
            <span class="brand__name">Videira</span>
            <span class="brand__tag">Engenharia e Serviços</span>
        </a>
        <nav class="nav-main" id="navegacao-principal" aria-label="Principal">
            <ul>
                <li><a href="#sobre">Sobre</a></li>
                <li><a href="#servicos">Serviços</a></li>
                <li><a href="#diferenciais">Diferenciais</a></li>
                <li><a href="#metodologia">Metodologia</a></li>
                <li><a href="#contato">Contato</a></li>
            </ul>
            <a href="#contato" class="btn btn--ghost">Fale conosco</a>
        </nav>
        <button type="button" class="nav-toggle" aria-expanded="false" aria-controls="navegacao-principal" aria-label="Abrir menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>

<main id="conteudo-principal">
    <section class="hero" aria-labelledby="hero-title">
        <div class="hero__media" aria-hidden="true">
            <img
                class="hero__parallax"
                src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=1920&q=80"
                alt=""
                width="1920"
                height="1080"
                fetchpriority="high"
            >
            <div class="hero__overlay"></div>
            <div class="hero__grain"></div>
        </div>
        <div class="container hero__content">
            <p class="hero__eyebrow">Engenharia aplicada à operação real</p>
            <h1 id="hero-title">Soluções completas em engenharia, HVAC, utilidades e operação industrial</h1>
            <p class="hero__lead">
                A Videira integra projeto, manutenção, PMOC, instalações, eficiência energética e gestão operacional para empresas que priorizam performance, conformidade e excelência técnica em ambientes críticos.
            </p>
            <div class="hero__actions">
                <a href="#contato" class="btn btn--primary">Solicitar orçamento</a>
                <a href="#servicos" class="btn btn--ghost">Conhecer serviços</a>
            </div>
        </div>
        <div class="hero__scroll" aria-hidden="true"></div>
    </section>

    <section class="credibility" aria-label="Compromissos da empresa">
        <div class="container credibility__grid">
            <div class="credibility__item reveal">
                <strong>Atendimento técnico especializado</strong>
                <p>Equipes com domínio de processos industriais, normas e desafios reais de campo.</p>
            </div>
            <div class="credibility__item reveal">
                <strong>Soluções sob demanda</strong>
                <p>Projetos e contratos moldados à sua operação — sem pacotes genéricos.</p>
            </div>
            <div class="credibility__item reveal">
                <strong>Foco em custo operacional</strong>
                <p>Redução de desperdício energético e paradas não planejadas com decisões baseadas em dados.</p>
            </div>
            <div class="credibility__item reveal">
                <strong>Operações críticas</strong>
                <p>Suporte a ambientes onde indisponibilidade e não conformidade não são opções.</p>
            </div>
        </div>
    </section>

    <section class="section" id="sobre">
        <div class="container">
            <header class="section__head reveal">
                <p class="section__label">Sobre nós</p>
                <h2>Parceria estratégica em engenharia e operação</h2>
                <p class="section__intro">
                    Unimos rigor técnico e visão de negócio para sustentar instalações complexas com segurança, previsibilidade e eficiência.
                </p>
            </header>
            <div class="about__grid">
                <div class="about__text reveal">
                    <p>
                        A <strong>Videira Engenharia e Serviços</strong> atua como extensão da sua equipe de manutenção e facilities: planejamos, executamos e acompanhamos intervenções em HVAC, utilidades e sistemas periféricos com padrão de documentação e transparência compatíveis com auditorias e operações de alto risco.
                    </p>
                    <p>
                        Do PMOC à modernização de ativos, passando por retrofit e gestão energética, nossa atuação é orientada a indicadores — MTBF, consumo, conformidade e continuidade — para que cada investimento se traduza em desempenho mensurável na planta.
                    </p>
                    <p>
                        Atendemos indústrias e infraestruturas que exigem resposta rápida, comunicação clara com engenharia e fornecedores, e execução impecável em campo. Esse é o nosso contrato implícito com cada cliente.
                    </p>
                </div>
                <figure class="about__visual reveal">
                    <img
                        src="https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=1200&q=80"
                        alt="Ambiente industrial com infraestrutura técnica e iluminação profissional"
                        width="1200"
                        height="900"
                        loading="lazy"
                    >
                    <figcaption class="about__stat">
                        <p>Entrega integrada</p>
                        <strong>Engenharia, operação e conformidade no mesmo fluxo</strong>
                    </figcaption>
                </figure>
            </div>
        </div>
    </section>

    <section class="section section--alt" id="servicos">
        <div class="container">
            <header class="section__head reveal">
                <p class="section__label">O que fazemos</p>
                <h2>Portfólio técnico orientado a resultado</h2>
                <p class="section__intro">
                    Capacidades complementares para cobrir o ciclo de vida dos seus sistemas — da concepção ao monitoramento contínuo.
                </p>
            </header>
            <div class="services__grid reveal-stagger">
                <article class="service-card reveal" style="--i:0">
                    <div class="service-card__media">
                        <img src="https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?auto=format&fit=crop&w=800&q=80" alt="" width="800" height="550" loading="lazy">
                    </div>
                    <div class="service-card__body">
                        <h3>Retrofit e renovações</h3>
                        <p>Modernização de sistemas legados com mínima interferência na produção e plano de transição seguro.</p>
                    </div>
                </article>
                <article class="service-card reveal" style="--i:1">
                    <div class="service-card__media">
                        <img src="https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=800&q=80" alt="" width="800" height="550" loading="lazy">
                    </div>
                    <div class="service-card__body">
                        <h3>Projetos</h3>
                        <p>Concepção e detalhamento executivo alinhados às normas vigentes e à realidade da sua operação.</p>
                    </div>
                </article>
                <article class="service-card reveal" style="--i:2">
                    <div class="service-card__media">
                        <img src="https://images.unsplash.com/photo-1621905252507-b35492cc74b4?auto=format&fit=crop&w=800&q=80" alt="" width="800" height="550" loading="lazy">
                    </div>
                    <div class="service-card__body">
                        <h3>Instalações de HVAC</h3>
                        <p>Implantação de climatização industrial e comercial com comissionamento e balanceamento rigorosos.</p>
                    </div>
                </article>
                <article class="service-card reveal" style="--i:3">
                    <div class="service-card__media">
                        <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=800&q=80" alt="" width="800" height="550" loading="lazy">
                    </div>
                    <div class="service-card__body">
                        <h3>PMOC</h3>
                        <p>Programas de manutenção, operação e controle documentados para atendimento à legislação e às melhores práticas.</p>
                    </div>
                </article>
                <article class="service-card reveal" style="--i:4">
                    <div class="service-card__media">
                        <img src="https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?auto=format&fit=crop&w=800&q=80" alt="" width="800" height="550" loading="lazy">
                    </div>
                    <div class="service-card__body">
                        <h3>Eficiência energética</h3>
                        <p>Diagnóstico, oportunidades de economia e acompanhamento de ganhos após implementação.</p>
                    </div>
                </article>
                <article class="service-card reveal" style="--i:5">
                    <div class="service-card__media">
                        <img src="https://images.unsplash.com/photo-1565514020126-2d1bd7d6e243?auto=format&fit=crop&w=800&q=80" alt="" width="800" height="550" loading="lazy">
                    </div>
                    <div class="service-card__body">
                        <h3>Manutenção e operação de utilidades</h3>
                        <p>Gestão de vapor, ar comprimido, água industrial e demais sistemas de apoio à produção.</p>
                    </div>
                </article>
                <article class="service-card reveal" style="--i:6">
                    <div class="service-card__media">
                        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80" alt="" width="800" height="550" loading="lazy">
                    </div>
                    <div class="service-card__body">
                        <h3>Gerenciamento de facilities</h3>
                        <p>Coordenação de prestadores, contratos e SLA com visão única do desempenho do parque físico.</p>
                    </div>
                </article>
                <article class="service-card reveal" style="--i:7">
                    <div class="service-card__media">
                        <img src="https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=800&q=80" alt="" width="800" height="550" loading="lazy">
                    </div>
                    <div class="service-card__body">
                        <h3>Manutenção e operação</h3>
                        <p>Rotinas preventivas e corretivas com priorização por criticidade e disponibilidade de ativos.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="section" id="diferenciais">
        <div class="container">
            <header class="section__head reveal">
                <p class="section__label">Posicionamento</p>
                <h2>Por que escolher a Videira</h2>
                <p class="section__intro">
                    Critérios que sustentam relacionamentos de longo prazo com empresas que não negociam qualidade operacional.
                </p>
            </header>
            <div class="diff__grid">
                <article class="diff-card reveal">
                    <div class="diff-card__icon" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                    </div>
                    <h3>Especialização técnica</h3>
                    <p>Profundidade em engenharia aplicada — não apenas execução de checklist.</p>
                </article>
                <article class="diff-card reveal">
                    <div class="diff-card__icon" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M2 14h4M10 10h4M18 16h4"/></svg>
                    </div>
                    <h3>Soluções sob medida</h3>
                    <p>Escopo e cronograma desenhados para o seu mix de ativos e restrições de processo.</p>
                </article>
                <article class="diff-card reveal">
                    <div class="diff-card__icon" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                    </div>
                    <h3>Eficiência operacional</h3>
                    <p>Menos retrabalho, mais previsibilidade e comunicação objetiva entre campo e gestão.</p>
                </article>
                <article class="diff-card reveal">
                    <div class="diff-card__icon" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                    </div>
                    <h3>Redução de custos fixos</h3>
                    <p>Otimização de consumo, paradas e contratos com foco no custo total de propriedade.</p>
                </article>
                <article class="diff-card reveal">
                    <div class="diff-card__icon" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12l2 2 4-4M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/></svg>
                    </div>
                    <h3>Conformidade</h3>
                    <p>Alinhamento a exigências legais, normas técnicas e políticas internas de HSE.</p>
                </article>
                <article class="diff-card reveal">
                    <div class="diff-card__icon" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                    </div>
                    <h3>Suporte especializado</h3>
                    <p>Interlocutores técnicos disponíveis para incidentes, planejamento e melhoria contínua.</p>
                </article>
                <article class="diff-card reveal">
                    <div class="diff-card__icon" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 19.5A2.5 2.5 0 016.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                    </div>
                    <h3>Padrão elevado de execução</h3>
                    <p>Disciplina em prazos, limpeza de obra e entrega documentada em cada etapa.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section section--alt" id="metodologia">
        <div class="container method">
            <header class="section__head reveal">
                <p class="section__label">Processo</p>
                <h2>Como atuamos</h2>
                <p class="section__intro">
                    Um método claro, replicável e transparente — da primeira visita à melhoria contínua pós-entrega.
                </p>
            </header>
            <div class="method__track reveal-stagger">
                <article class="method-step">
                    <h3>Diagnóstico técnico</h3>
                    <p>Levantamento de ativos, riscos, desvios de performance e oportunidades de melhoria.</p>
                </article>
                <article class="method-step">
                    <h3>Planejamento</h3>
                    <p>Escopo, cronograma, recursos e indicadores de sucesso alinhados com sua equipe.</p>
                </article>
                <article class="method-step">
                    <h3>Execução</h3>
                    <p>Implementação com controle de qualidade, segurança e comunicação periódica.</p>
                </article>
                <article class="method-step">
                    <h3>Acompanhamento</h3>
                    <p>Monitoramento de resultados, relatórios e ajustes finos após go-live.</p>
                </article>
                <article class="method-step">
                    <h3>Otimização</h3>
                    <p>Ciclos de revisão para sustentar ganhos e antecipar necessidades futuras.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="cta-band" aria-labelledby="cta-title">
        <div class="container">
            <div>
                <h2 id="cta-title">Sua operação precisa de mais eficiência, segurança e confiabilidade?</h2>
                <p class="cta-band__text">Fale com a Videira e conheça uma solução técnica sob medida para a sua empresa — com escopo claro e compromisso mensurável.</p>
            </div>
            <div class="cta-band__actions">
                <a href="#contato" class="btn btn--primary">Solicitar orçamento</a>
                <a href="https://wa.me/{{ $wa }}" class="btn btn--whatsapp" target="_blank" rel="noopener noreferrer">Falar no WhatsApp</a>
            </div>
        </div>
    </section>

    <section class="section" id="contato">
        <div class="container">
            <header class="section__head reveal">
                <p class="section__label">Contato</p>
                <h2>Conecte-se com a nossa equipe</h2>
                <p class="section__intro">
                    Envie sua demanda ou agende uma conversa técnica. Retornamos com proposta de próximos passos.
                </p>
            </header>
            <div class="contact__grid">
                <form
                    class="contact-form reveal"
                    id="form-contato"
                    data-mailto="{{ config('videira.contact_email') }}"
                    novalidate
                >
                    <div>
                        <label for="nome">Nome</label>
                        <input type="text" id="nome" name="nome" required autocomplete="name" placeholder="Seu nome">
                    </div>
                    <div>
                        <label for="email">E-mail corporativo</label>
                        <input type="email" id="email" name="email" required autocomplete="email" placeholder="nome@empresa.com.br">
                    </div>
                    <div>
                        <label for="empresa">Empresa</label>
                        <input type="text" id="empresa" name="empresa" autocomplete="organization" placeholder="Razão social ou unidade">
                    </div>
                    <div>
                        <label for="mensagem">Mensagem</label>
                        <textarea id="mensagem" name="mensagem" required placeholder="Descreva brevemente a demanda, localização e prazo desejado."></textarea>
                    </div>
                    <button type="submit" class="btn btn--primary" style="width:100%">Enviar mensagem</button>
                </form>
                <aside class="contact-aside reveal">
                    <h3>Canais diretos</h3>
                    <ul class="contact-list">
                        <li>
                            <span>Telefone</span>
                            <a href="tel:{{ preg_replace('/\D/', '', config('videira.phone')) }}">{{ config('videira.phone') }}</a>
                        </li>
                        <li>
                            <span>E-mail</span>
                            <a href="mailto:{{ config('videira.contact_email') }}">{{ config('videira.contact_email') }}</a>
                        </li>
                        <li>
                            <span>WhatsApp</span>
                            <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener noreferrer">Iniciar conversa</a>
                        </li>
                        @if($address)
                        <li>
                            <span>Localização</span>
                            {{ $address }}
                        </li>
                        @endif
                    </ul>
                    @if($mapsUrl)
                    <div class="contact-map">
                        <iframe src="{{ $mapsUrl }}" title="Mapa — Videira Engenharia e Serviços" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>
</main>

<footer class="site-footer" role="contentinfo">
    <div class="container site-footer__grid">
        <div>
            <div class="brand">
                <span class="brand__name">Videira</span>
                <span class="brand__tag">Engenharia e Serviços</span>
            </div>
            <p class="tagline">Engenharia com método, execução com padrão e parceria com visão de longo prazo.</p>
        </div>
        <div>
            <h4>Navegação</h4>
            <ul>
                <li><a href="#sobre">Sobre</a></li>
                <li><a href="#servicos">Serviços</a></li>
                <li><a href="#metodologia">Metodologia</a></li>
                <li><a href="#contato">Contato</a></li>
            </ul>
        </div>
        <div>
            <h4>Contato</h4>
            <ul>
                <li><a href="tel:{{ preg_replace('/\D/', '', config('videira.phone')) }}">{{ config('videira.phone') }}</a></li>
                <li><a href="mailto:{{ config('videira.contact_email') }}">{{ config('videira.contact_email') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="container site-footer__bottom">
        <span>© {{ date('Y') }} Videira Engenharia e Serviços. Todos os direitos reservados.</span>
        <span>Desempenho, conformidade e continuidade para operações industriais.</span>
    </div>
</footer>

<div class="internal-access">
    <a href="{{ route('login') }}" title="Acesso reservado à equipe Videira">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
        <span>Área interna</span>
    </a>
</div>

</body>
</html>
