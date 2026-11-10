<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="{{ asset('logo/localpro-mark.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f7f2">
    <meta name="description" content="Find trusted local professionals in Ho and across the Volta Region.">
    <title>LocalPro — Good people. Good work.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/ts/app/app.ts'])
    <style>
        .home-page .how-section,
        .how-section {
            position: relative !important;
            overflow: hidden !important;
            padding: 80px 24px !important;
            background: radial-gradient(ellipse at 50% 0%, #155e4e 0%, transparent 70%),
                        linear-gradient(135deg, #062b23 0%, #0c3d33 50%, #062720 100%) !important;
            color: #ffffff !important;
            text-align: center !important;
        }
        .home-page .how-section .eyebrow.light,
        .how-section .eyebrow.light {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            padding: 6px 18px !important;
            background: rgba(45, 212, 191, 0.14) !important;
            border: 1px solid rgba(94, 234, 212, 0.38) !important;
            border-radius: 999px !important;
            color: #5eead4 !important;
            font-weight: 800 !important;
            font-size: 12px !important;
            letter-spacing: 1.8px !important;
            text-transform: uppercase !important;
            margin-bottom: 8px !important;
        }
        .home-page .how-section h2,
        .how-section h2 {
            color: #ffffff !important;
            font-size: clamp(32px, 3.5vw, 44px) !important;
            font-weight: 800 !important;
            letter-spacing: -1.2px !important;
            margin: 14px 0 12px !important;
            text-shadow: 0 2px 16px rgba(0, 0, 0, 0.4) !important;
        }
        .home-page .how-section > p,
        .how-section > p {
            color: #e2f4ec !important;
            font-size: 16.5px !important;
            line-height: 1.65 !important;
            max-width: 640px !important;
            margin: 0 auto 36px !important;
            text-shadow: 0 1px 6px rgba(0, 0, 0, 0.25) !important;
        }
        .home-page .steps,
        .steps {
            max-width: 980px !important;
            margin: 0 auto 36px !important;
            display: grid !important;
            grid-template-columns: 1fr 45px 1fr 45px 1fr !important;
            gap: 16px !important;
            align-items: center !important;
            text-align: left !important;
        }
        .home-page .steps article,
        .steps article {
            position: relative !important;
            min-height: 200px !important;
            padding: 26px 24px !important;
            border: 1.5px solid rgba(255, 255, 255, 0.24) !important;
            border-top: 2.5px solid #34d399 !important;
            border-radius: 20px !important;
            background: rgba(255, 255, 255, 0.12) !important;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.22) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease, background 0.25s ease !important;
        }
        .home-page .steps article:hover,
        .steps article:hover {
            transform: translateY(-5px) !important;
            border-color: #5eead4 !important;
            background: rgba(255, 255, 255, 0.18) !important;
            box-shadow: 0 22px 50px rgba(0, 0, 0, 0.32) !important;
        }
        .home-page .steps article > small,
        .steps article > small {
            display: inline-flex !important;
            align-items: center !important;
            padding: 3px 10px !important;
            border-radius: 999px !important;
            background: rgba(94, 234, 212, 0.2) !important;
            border: 1px solid rgba(94, 234, 212, 0.45) !important;
            color: #5eead4 !important;
            font-size: 12px !important;
            font-weight: 800 !important;
            letter-spacing: 1.6px !important;
            margin-bottom: 16px !important;
        }
        .home-page .steps article > span,
        .steps article > span {
            width: 48px !important;
            height: 48px !important;
            border-radius: 14px !important;
            background: linear-gradient(135deg, #2dd4bf 0%, #059669 100%) !important;
            color: #02261e !important;
            display: grid !important;
            place-items: center !important;
            box-shadow: 0 8px 22px rgba(45, 212, 191, 0.4) !important;
            border: 0 !important;
        }
        .home-page .steps article > span svg,
        .steps article > span svg {
            width: 23px !important;
            height: 23px !important;
            stroke: #02261e !important;
            stroke-width: 2.6 !important;
        }
        .home-page .steps h3,
        .steps h3 {
            color: #ffffff !important;
            font-size: 19px !important;
            font-weight: 800 !important;
            letter-spacing: -0.4px !important;
            margin: 16px 0 8px !important;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.3) !important;
        }
        .home-page .steps p,
        .steps p {
            color: #e6f6ee !important;
            font-size: 14.5px !important;
            line-height: 1.65 !important;
            margin: 0 !important;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.25) !important;
        }
        .home-page .steps > i,
        .steps > i {
            height: 2px !important;
            border-top: 2px dashed rgba(94, 234, 212, 0.65) !important;
            margin-inline: 4px !important;
            transform: translateY(-24px) !important;
        }
        .home-page .how-section > a,
        .how-section > a {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            margin-top: 28px !important;
            padding: 13px 32px !important;
            border-radius: 999px !important;
            border: 1px solid rgba(94, 234, 212, 0.5) !important;
            background: linear-gradient(135deg, #2dd4bf, #059669) !important;
            color: #022018 !important;
            font-size: 15px !important;
            font-weight: 800 !important;
            text-decoration: none !important;
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.38) !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease !important;
        }
        .home-page .how-section > a:hover,
        .how-section > a:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 12px 30px rgba(45, 212, 191, 0.5) !important;
            filter: brightness(1.08) !important;
            color: #011711 !important;
        }
        .home-page .how-section > a svg,
        .how-section > a svg {
            width: 18px !important;
            height: 18px !important;
            stroke-width: 2.5 !important;
        }
        @media (max-width: 720px) {
            .home-page .how-section,
            .how-section {
                padding: 54px 20px !important;
            }
            .home-page .steps,
            .steps {
                grid-template-columns: 1fr !important;
                max-width: 380px !important;
                gap: 16px !important;
                margin: 28px auto !important;
            }
            .home-page .steps article,
            .steps article {
                min-height: 0 !important;
                padding: 20px 20px 20px 72px !important;
                border: 1.5px solid rgba(255, 255, 255, 0.22) !important;
                border-top: 2.5px solid #34d399 !important;
                background: rgba(255, 255, 255, 0.12) !important;
                border-radius: 16px !important;
            }
            .home-page .steps article > small,
            .steps article > small {
                position: static !important;
                margin-bottom: 6px !important;
            }
            .home-page .steps article > span,
            .steps article > span {
                position: absolute !important;
                left: 14px !important;
                top: 20px !important;
                width: 44px !important;
                height: 44px !important;
            }
            .home-page .steps > i,
            .steps > i {
                display: none !important;
            }
        }

        /* ========================================================
           JOIN SECTION REDESIGN
           ======================================================== */
        .home-page .join-section,
        .join-section {
            position: relative !important;
            overflow: hidden !important;
            width: min(1160px, calc(100% - 44px)) !important;
            margin: 88px auto !important;
            padding: 56px clamp(28px, 5.5vw, 68px) !important;
            border-radius: 28px !important;
            border: 1px solid #d3e9dc !important;
            background: radial-gradient(circle at 92% 24%, rgba(45, 212, 191, 0.22) 0%, transparent 48%),
                        radial-gradient(circle at 10% 90%, rgba(16, 185, 129, 0.12) 0%, transparent 35%),
                        linear-gradient(135deg, #f0f8f3 0%, #e8f5ec 52%, #dff0e6 100%) !important;
            box-shadow: 0 20px 50px -15px rgba(18, 58, 45, 0.1) !important;
            display: grid !important;
            grid-template-columns: 1.15fr 0.85fr !important;
            gap: 48px !important;
            align-items: center !important;
        }

        .home-page .join-section:before,
        .home-page .join-section:after,
        .join-section:before,
        .join-section:after {
            display: none !important;
        }
        .home-page .join-section aside > span:before,
        .join-section aside > span:before {
            content: none !important;
            display: none !important;
        }

        .home-page .join-content,
        .join-content {
            position: relative !important;
            z-index: 2 !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            text-align: left !important;
        }

        .home-page .join-eyebrow,
        .join-eyebrow {
            display: inline-flex !important;
            align-items: center !important;
            gap: 7px !important;
            color: #065f46 !important;
            font-size: 12px !important;
            font-weight: 800 !important;
            letter-spacing: 1.6px !important;
            text-transform: uppercase !important;
            margin-bottom: 12px !important;
        }
        .home-page .join-eyebrow svg,
        .join-eyebrow svg {
            width: 15px !important;
            height: 15px !important;
            stroke-width: 2.2 !important;
            color: #10b981 !important;
        }

        .home-page .join-section h2,
        .join-section h2 {
            color: #0d2821 !important;
            font-size: clamp(30px, 3.2vw, 42px) !important;
            font-weight: 800 !important;
            line-height: 1.18 !important;
            letter-spacing: -1.2px !important;
            margin: 0 0 14px !important;
        }

        .home-page .join-section p,
        .join-section p {
            color: #4a6358 !important;
            font-size: 16px !important;
            line-height: 1.65 !important;
            margin: 0 0 22px !important;
            max-width: 520px !important;
        }

        .home-page .join-perks,
        .join-perks {
            list-style: none !important;
            margin: 0 0 30px !important;
            padding: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 10px !important;
        }
        .home-page .join-perks li,
        .join-perks li {
            display: inline-flex !important;
            align-items: center !important;
            gap: 10px !important;
            color: #1a4235 !important;
            font-size: 14.5px !important;
            font-weight: 600 !important;
        }
        .home-page .join-perks li svg,
        .join-perks li svg {
            width: 18px !important;
            height: 18px !important;
            flex-shrink: 0 !important;
            stroke-width: 2.3 !important;
            color: #059669 !important;
        }

        .home-page .join-cta-btn,
        .join-cta-btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 10px !important;
            padding: 14px 32px !important;
            border-radius: 12px !important;
            background: linear-gradient(135deg, #077963 0%, #065b4c 100%) !important;
            color: #ffffff !important;
            font-size: 15px !important;
            font-weight: 750 !important;
            text-decoration: none !important;
            box-shadow: 0 8px 24px rgba(7, 121, 99, 0.3) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            transition: all 0.2s ease !important;
        }
        .home-page .join-cta-btn:hover,
        .join-cta-btn:hover {
            background: linear-gradient(135deg, #098e74 0%, #086b59 100%) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 12px 30px rgba(7, 121, 99, 0.4) !important;
            color: #ffffff !important;
        }
        .home-page .join-cta-btn svg,
        .join-cta-btn svg {
            width: 18px !important;
            height: 18px !important;
            stroke-width: 2.3 !important;
            transition: transform 0.2s ease !important;
        }
        .home-page .join-cta-btn:hover svg,
        .join-cta-btn:hover svg {
            transform: translateX(4px) !important;
        }

        .home-page .join-visual,
        .join-visual {
            position: relative !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            padding: 10px !important;
        }

        .home-page .join-card-glow,
        .join-card-glow {
            position: absolute !important;
            width: 280px !important;
            height: 280px !important;
            background: radial-gradient(circle, rgba(45, 212, 191, 0.35) 0%, rgba(16, 185, 129, 0.1) 60%, transparent 80%) !important;
            filter: blur(28px) !important;
            pointer-events: none !important;
            z-index: 1 !important;
        }

        .home-page .join-card,
        .join-card {
            position: relative !important;
            z-index: 2 !important;
            width: 100% !important;
            max-width: 360px !important;
            min-height: auto !important;
            height: auto !important;
            background: rgba(255, 255, 255, 0.94) !important;
            backdrop-filter: blur(14px) !important;
            border: 1px solid rgba(255, 255, 255, 0.8) !important;
            border-radius: 22px !important;
            padding: 28px 24px !important;
            box-shadow: 0 20px 45px -8px rgba(18, 54, 43, 0.12), 0 1px 3px rgba(0, 0, 0, 0.04) !important;
            display: flex !important;
            flex-direction: column !important;
            text-align: left !important;
            transition: transform 0.25s ease, box-shadow 0.25s ease !important;
        }
        .home-page .join-card:hover,
        .join-card:hover {
            transform: translateY(-4px) !important;
            box-shadow: 0 26px 55px -10px rgba(18, 54, 43, 0.18) !important;
        }

        .home-page .join-badge-top,
        .join-badge-top {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            margin-bottom: 18px !important;
        }

        .home-page .join-shield-icon,
        .join-shield-icon {
            width: 48px !important;
            height: 48px !important;
            border-radius: 14px !important;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%) !important;
            border: 1px solid #a7f3d0 !important;
            color: #059669 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15) !important;
        }
        .home-page .join-shield-icon svg,
        .join-shield-icon svg {
            width: 24px !important;
            height: 24px !important;
            stroke-width: 2.2 !important;
        }

        .home-page .join-verified-label,
        .join-verified-label {
            display: inline-flex !important;
            align-items: center !important;
            gap: 5px !important;
            padding: 5px 12px !important;
            background: #ecfdf5 !important;
            border: 1px solid #a7f3d0 !important;
            border-radius: 999px !important;
            color: #047857 !important;
            font-size: 12px !important;
            font-weight: 750 !important;
        }
        .home-page .join-verified-label svg,
        .join-verified-label svg {
            width: 14px !important;
            height: 14px !important;
            stroke-width: 2.5 !important;
        }

        .home-page .join-card strong,
        .join-card strong {
            color: #0f2d24 !important;
            font-size: 19px !important;
            font-weight: 800 !important;
            line-height: 1.3 !important;
            letter-spacing: -0.4px !important;
            margin-bottom: 8px !important;
        }

        .home-page .join-card small,
        .join-card small {
            color: #556e63 !important;
            font-size: 13px !important;
            line-height: 1.55 !important;
            margin-bottom: 22px !important;
        }

        .home-page .join-card-stats,
        .join-card-stats {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            padding-top: 16px !important;
            border-top: 1px solid #eaf2ed !important;
        }

        .home-page .join-stat,
        .join-stat {
            display: flex !important;
            flex-direction: column !important;
            gap: 2px !important;
        }
        .home-page .join-stat-num,
        .join-stat-num {
            display: inline-flex !important;
            align-items: center !important;
            gap: 4px !important;
            color: #065f46 !important;
            font-size: 15px !important;
            font-weight: 800 !important;
        }
        .home-page .join-stat-num svg,
        .join-stat-num svg {
            width: 14px !important;
            height: 14px !important;
            fill: #f59e0b !important;
            color: #f59e0b !important;
        }
        .home-page .join-stat-lbl,
        .join-stat-lbl {
            color: #728c81 !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }

        .home-page .join-stat-divider,
        .join-stat-divider {
            width: 1px !important;
            height: 28px !important;
            background: #e2ede6 !important;
        }

        /* ========================================================
           HOME FAQ REDESIGN
           ======================================================== */
        .home-page .home-faq,
        .home-faq {
            padding-top: 50px !important;
            padding-bottom: 96px !important;
        }

        .home-page .faq-container,
        .faq-container {
            width: min(920px, 100%) !important;
            margin: 0 auto !important;
        }

        .home-page .faq-heading,
        .faq-heading {
            margin-bottom: 34px !important;
        }
        .home-page .faq-heading .eyebrow,
        .faq-heading .eyebrow {
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            color: #047857 !important;
            font-size: 12px !important;
            font-weight: 800 !important;
            letter-spacing: 1.4px !important;
            margin-bottom: 8px !important;
        }
        .home-page .faq-heading .eyebrow svg,
        .faq-heading .eyebrow svg {
            width: 15px !important;
            height: 15px !important;
            stroke-width: 2.2 !important;
        }
        .home-page .faq-heading h2,
        .faq-heading h2 {
            color: #0e2b22 !important;
            font-size: clamp(28px, 2.8vw, 38px) !important;
            font-weight: 800 !important;
            letter-spacing: -1px !important;
            margin: 0 0 8px !important;
        }
        .home-page .faq-heading p,
        .faq-heading p {
            color: #5d756b !important;
            font-size: 16px !important;
            margin: 0 !important;
        }

        .home-page .faq-list,
        .faq-list {
            display: flex !important;
            flex-direction: column !important;
            gap: 14px !important;
            margin: 28px 0 38px !important;
        }

        .home-page .home-faq details,
        .home-faq details {
            position: relative !important;
            border: 1px solid #d9e7df !important;
            border-radius: 16px !important;
            background: #ffffff !important;
            box-shadow: 0 4px 14px rgba(18, 54, 43, 0.03) !important;
            padding: 0 !important;
            overflow: hidden !important;
            transition: all 0.24s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .home-page .home-faq details:hover,
        .home-faq details:hover {
            border-color: #86efac !important;
            box-shadow: 0 8px 24px rgba(18, 54, 43, 0.06) !important;
            transform: translateY(-1px) !important;
        }
        .home-page .home-faq details[open],
        .home-faq details[open] {
            border-color: #10b981 !important;
            background: #fbfdfc !important;
            box-shadow: 0 10px 28px rgba(16, 185, 129, 0.09) !important;
        }

        .home-page .home-faq summary,
        .home-faq summary {
            list-style: none !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 16px !important;
            padding: 20px 24px !important;
            color: #12362b !important;
            font-size: 16.5px !important;
            font-weight: 700 !important;
            letter-spacing: -0.2px !important;
            cursor: pointer !important;
            user-select: none !important;
            transition: color 0.18s ease !important;
        }
        .home-page .home-faq summary::-webkit-details-marker,
        .home-faq summary::-webkit-details-marker {
            display: none !important;
        }
        .home-page .home-faq summary:hover,
        .home-faq summary:hover {
            color: #065f46 !important;
        }

        .home-page .faq-toggle,
        .faq-toggle {
            width: 32px !important;
            height: 32px !important;
            flex-shrink: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 50% !important;
            background: #f0f7f3 !important;
            border: 1px solid #d8ebd0 !important;
            color: #047857 !important;
            transition: all 0.24s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .home-page .faq-toggle svg,
        .faq-toggle svg {
            width: 17px !important;
            height: 17px !important;
            stroke-width: 2.4 !important;
            transition: transform 0.24s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .home-page .home-faq details[open] .faq-toggle,
        .home-faq details[open] .faq-toggle {
            background: #10b981 !important;
            border-color: #10b981 !important;
            color: #ffffff !important;
            transform: rotate(180deg) !important;
        }

        .home-page .faq-answer,
        .faq-answer {
            padding: 0 24px 22px 24px !important;
            border-top: 1px solid #eef5f1 !important;
            padding-top: 16px !important;
            animation: faqSlideDown 0.24s ease-out !important;
        }

        .home-page .faq-answer p,
        .faq-answer p {
            color: #4a6358 !important;
            font-size: 15px !important;
            line-height: 1.7 !important;
            margin: 0 !important;
            max-width: none !important;
        }

        /* Support Box CTA */
        .home-page .faq-support-card,
        .faq-support-card {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 20px !important;
            padding: 24px 28px !important;
            background: linear-gradient(135deg, #f2f9f5 0%, #eaf5ee 100%) !important;
            border: 1px solid #d2ebd9 !important;
            border-radius: 18px !important;
            box-shadow: 0 6px 20px rgba(18, 54, 43, 0.04) !important;
            margin-top: 36px !important;
        }

        .home-page .faq-support-info,
        .faq-support-info {
            display: flex !important;
            align-items: center !important;
            gap: 16px !important;
        }
        .home-page .faq-support-icon,
        .faq-support-icon {
            width: 44px !important;
            height: 44px !important;
            flex-shrink: 0 !important;
            border-radius: 12px !important;
            background: #ffffff !important;
            border: 1px solid #cce8d6 !important;
            color: #059669 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1) !important;
        }
        .home-page .faq-support-icon svg,
        .faq-support-icon svg {
            width: 22px !important;
            height: 22px !important;
            stroke-width: 2.2 !important;
        }
        .home-page .faq-support-info strong,
        .faq-support-info strong {
            display: block !important;
            color: #0e2b22 !important;
            font-size: 16px !important;
            font-weight: 750 !important;
            margin-bottom: 3px !important;
        }
        .home-page .faq-support-info p,
        .faq-support-info p {
            margin: 0 !important;
            color: #556e64 !important;
            font-size: 13.5px !important;
            line-height: 1.45 !important;
        }

        .home-page .faq-support-btn,
        .faq-support-btn {
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            padding: 11px 22px !important;
            background: #ffffff !important;
            border: 1.5px solid #087b65 !important;
            border-radius: 10px !important;
            color: #087b65 !important;
            font-size: 14px !important;
            font-weight: 750 !important;
            text-decoration: none !important;
            white-space: nowrap !important;
            transition: all 0.18s ease !important;
            box-shadow: 0 2px 8px rgba(8, 123, 101, 0.08) !important;
        }
        .home-page .faq-support-btn:hover,
        .faq-support-btn:hover {
            background: #087b65 !important;
            color: #ffffff !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 18px rgba(8, 123, 101, 0.25) !important;
        }
        .home-page .faq-support-btn svg,
        .faq-support-btn svg {
            width: 16px !important;
            height: 16px !important;
            stroke-width: 2.4 !important;
            transition: transform 0.18s ease !important;
        }
        .home-page .faq-support-btn:hover svg,
        .faq-support-btn:hover svg {
            transform: translateX(3px) !important;
        }

        @media (max-width: 860px) {
            .home-page .join-section,
            .join-section {
                grid-template-columns: 1fr !important;
                gap: 36px !important;
                padding: 40px 24px !important;
            }
            .home-page .join-card,
            .join-card {
                max-width: 100% !important;
            }
            .home-page .faq-support-card,
            .faq-support-card {
                flex-direction: column !important;
                align-items: flex-start !important;
                padding: 20px !important;
            }
            .home-page .faq-support-btn,
            .faq-support-btn {
                width: 100% !important;
                justify-content: center !important;
            }
        }
    </style>
</head>
<body id="top" class="home-page public-site">
<header class="topbar">
    <a class="brand" href="{{ route('home') }}">
        <span class="brand-mark">
            <img class="brand-logo-mark" src="{{ asset('logo/localpro-mark.png') }}" alt="">
        </span>
        <span>local<span>pro</span>
        <small>VOLTA REGION</small></span>
    </a>

    <nav class="main-nav">
        <a href="{{ route('services.index') }}"><i data-lucide="briefcase-business" aria-hidden="true"></i> {{ __('ui.services') }}</a>
        <a href="{{ route('how-it-works') }}"><i data-lucide="circle-help" aria-hidden="true"></i> {{ __('ui.how_it_works') }}</a>
        <a href="{{ route('professionals.index') }}"><i data-lucide="search" aria-hidden="true"></i> {{ __('ui.find_professional') }}</a>
    </nav>

    <div class="top-actions">
        @include('components.language-picker.language-picker')
        <span class="location-chip">
            <i data-lucide="map-pin" aria-hidden="true"></i> Ho, Volta Region
        </span>
        @auth
            @include('components.navbar.account-menu')
        @else
            <a class="login-link" href="{{ route('login') }}" data-open-auth="login">{{ __('ui.log_in') }}</a>
            <a class="button button-small" href="{{ route('register') }}" data-open-auth="register">{{ __('ui.sign_up') }}</a>
        @endauth
    </div>
</header>
@include('components.auth-modals.auth-modals')
<main>
    @if(session('status'))<div class="flash-note">  {{ session('status') }}</div>@endif
    <section class="hero">
        <div class="hero-copy">
            <div class="eyebrow"><i data-lucide="sparkles" aria-hidden="true"></i> {{ __('ui.home_eyebrow') }}</div>
            <h1>{{ __('ui.hero_title_first') }}<br>{{ __('ui.hero_title_second') }}</h1>
            <p>{{ __('ui.hero_description') }}</p>
            <form class="search-panel" action="{{ route('professionals.index') }}" method="GET">
                <label class="search-field"><i data-lucide="search" aria-hidden="true"></i><span><small>{{ __('ui.search_placeholder') }}</small><input type="search" name="q" value="{{ request('q') }}" placeholder="Try &quot;fix a leaking tap&quot;"></span></label>
                <span class="search-divider"></span>
                <label class="search-field search-location"><i data-lucide="map-pin" aria-hidden="true"></i><span><small>{{ __('ui.home_search_area') }}</small><select name="city"><option value="Ho" @selected(request('city', 'Ho') === 'Ho')>Ho, Volta Region</option><option value="Hohoe" @selected(request('city') === 'Hohoe')>Hohoe, Volta Region</option><option value="Keta" @selected(request('city') === 'Keta')>Keta, Volta Region</option><option value="Aflao" @selected(request('city') === 'Aflao')>Aflao, Volta Region</option></select></span></label>
                <button class="button search-button" type="submit"><i data-lucide="search" aria-hidden="true"></i>{{ __('ui.home_search_action') }}</button>
            </form>
            <div class="popular"><span>{{ __('ui.home_popular') }}</span><a href="{{ route('professionals.index', ['q' => 'Plumber', 'city' => 'Ho']) }}">Plumber</a><a href="{{ route('professionals.index', ['q' => 'Electrician', 'city' => 'Ho']) }}">Electrician</a><a href="{{ route('professionals.index', ['q' => 'AC technician', 'city' => 'Ho']) }}">AC technician</a></div>
            <div class="hero-note"><span class="people-dots"><i>K</i><i>A</i><i>S</i><b>+</b></span><span><strong>{{ __('ui.home_local_trust') }}</strong><small>{{ __('ui.home_community_pros') }}</small></span><span class="note-rule"></span><span><strong class="rating">4.8 <i data-lucide="star" aria-hidden="true"></i></strong><small>{{ __('ui.home_verified_reviews') }}</small></span></div>
        </div>
        <div class="hero-art" aria-label="Illustration of a local service professional at work" role="img"><div class="art-circle"></div><div class="art-orbit"></div><div class="art-spark"><i data-lucide="sparkles" aria-hidden="true"></i></div><div class="worker"><div class="worker-head"><div class="worker-hair"></div><div class="worker-face"><i></i><i></i><b></b></div></div><div class="worker-neck"></div><div class="worker-body"><span></span></div><div class="worker-arm arm-one"></div><div class="worker-arm arm-two"></div><div class="worker-box"><i></i></div></div><div class="float-tag verified-tag"><span><i data-lucide="shield-check" aria-hidden="true"></i></span><div><strong>Verified professional</strong><small>Identity checked by LocalPro</small></div></div><div class="float-tag price-tag"><b><i data-lucide="circle-dollar-sign" aria-hidden="true"></i></b><div><strong>Clear quotes, no surprises</strong><small>Agree on price before work starts</small></div></div><span class="art-star"><i data-lucide="sparkles" aria-hidden="true"></i></span></div>
    </section>
    <section class="trust-row"><span><i data-lucide="shield-check" aria-hidden="true"></i>{{ __('ui.home_trust_verified') }}</span><b></b><span><i class="gold" data-lucide="credit-card" aria-hidden="true"></i>{{ __('ui.home_trust_prices') }}</span><b></b><span><i data-lucide="map-pin" aria-hidden="true"></i>{{ __('ui.home_trust_location') }}</span></section>
    <section class="section categories" id="services"><div class="section-heading"><div><div class="eyebrow muted">{{ __('ui.home_start_here') }}</div><h2>{{ __('ui.home_help_heading') }}</h2><p>{{ __("ui.home_help_intro") }}</p></div><a class="text-link" href="{{ route('services.index') }}">{{ __("ui.explore_services") }} <i data-lucide="arrow-right" aria-hidden="true"></i></a></div>
        <div class="category-grid">
            @forelse($categories as $category)
                <x-service-card.service-card :category="$category" />
            @empty
                <div class="category-empty">{{ __('ui.home_empty_services') }}</div>
            @endforelse
        </div>
    </section>
    <section class="section provider-section" id="providers"><div class="section-heading"><div><div class="eyebrow muted">{{ __('ui.home_provider_kicker') }}</div><h2>{{ __('ui.home_provider_heading') }}</h2><p>{{ __('ui.home_provider_intro') }}</p></div><span class="verified-filter"><i data-lucide="shield-check" aria-hidden="true"></i> Verified professionals</span></div>
        @if($providers->isNotEmpty())
            <div class="provider-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(min(100%,25rem),25rem));gap:18px">@foreach($providers as $provider)<x-provider-card.provider-card :provider="$provider" />@endforeach</div>
        @else
            <div class="provider-empty"><div class="empty-icon"><i data-lucide="users-round" aria-hidden="true"></i></div><div><strong>{{ __('ui.home_empty_providers') }}</strong><p>{{ __('ui.home_provider_joining') }}</p></div><a href="#join">{{ __('ui.home_join_question') }} <b>Join us <i data-lucide="arrow-right" aria-hidden="true"></i></b></a></div>
        @endif
    </section>
    <section class="how-section" id="how">
        <div class="eyebrow light"><i data-lucide="sparkles" aria-hidden="true"></i> {{ __('ui.home_how_kicker') }}</div>
        <h2>{{ __('ui.home_how_heading') }}</h2>
        <p>{{ __('ui.home_how_intro') }}</p>
        <div class="steps">
            <article>
                <small>01</small>
                <span><i data-lucide="search" aria-hidden="true"></i></span>
                <h3>{{ __('ui.home_step_search') }}</h3>
                <p>{{ __('ui.home_step_search_text') }}</p>
            </article>
            <i></i>
            <article>
                <small>02</small>
                <span><i data-lucide="message-circle" aria-hidden="true"></i></span>
                <h3>{{ __('ui.home_step_message') }}</h3>
                <p>{{ __('ui.home_step_message_text') }}</p>
            </article>
            <i></i>
            <article>
                <small>03</small>
                <span><i data-lucide="check" aria-hidden="true"></i></span>
                <h3>{{ __('ui.home_step_done') }}</h3>
                <p>{{ __('ui.home_step_done_text') }}</p>
            </article>
        </div>
        <a href="#services">{{ __("ui.home_search_action") }} <i data-lucide="arrow-right" aria-hidden="true"></i></a>
    </section>
    <section class="join-section" id="join">
        <div class="join-content">
            <div class="eyebrow join-eyebrow"><i data-lucide="sparkles" aria-hidden="true"></i> GOOD AT WHAT YOU DO?</div>
            <h2>Your next customer is<br>closer than you think.</h2>
            <p>Join local professionals building their reputation and finding steady work on LocalPro across Ho and the Volta Region.</p>
            <ul class="join-perks">
                <li><i data-lucide="check" aria-hidden="true"></i> No upfront listing fees — join free</li>
                <li><i data-lucide="check" aria-hidden="true"></i> Direct customer requests in your area</li>
                <li><i data-lucide="check" aria-hidden="true"></i> Build verified reviews that stay with you</li>
            </ul>
            <a class="button join-cta-btn" href="{{ route('register', ['role' => 'provider']) }}">Become a LocalPro <i data-lucide="arrow-right" aria-hidden="true"></i></a>
        </div>
        <div class="join-visual">
            <div class="join-card-glow"></div>
            <aside class="join-card">
                <div class="join-badge-top">
                    <span class="join-shield-icon"><i data-lucide="shield-check" aria-hidden="true"></i></span>
                    <span class="join-verified-label"><i data-lucide="shield-check" aria-hidden="true"></i> Verified LocalPro</span>
                </div>
                <strong>Good work deserves<br>to be seen.</strong>
                <small>Start building your verified local reputation and connect directly with high-intent clients.</small>
                <div class="join-card-stats">
                    <div class="join-stat">
                        <span class="join-stat-num">4.9 <i data-lucide="star" aria-hidden="true"></i></span>
                        <span class="join-stat-lbl">Rating</span>
                    </div>
                    <div class="join-stat-divider"></div>
                    <div class="join-stat">
                        <span class="join-stat-num">100%</span>
                        <span class="join-stat-lbl">Direct Pay</span>
                    </div>
                    <div class="join-stat-divider"></div>
                    <div class="join-stat">
                        <span class="join-stat-num">Ho &amp; Volta</span>
                        <span class="join-stat-lbl">Community</span>
                    </div>
                </div>
            </aside>
        </div>
    </section>
    <section class="section home-faq" id="answers">
        <div class="faq-container">
            <div class="section-heading faq-heading">
                <div>
                    <div class="eyebrow muted"><i data-lucide="circle-help" aria-hidden="true"></i> HELPFUL DETAILS BEFORE YOU BOOK</div>
                    <h2>Common questions, clear answers.</h2>
                    <p>Know what to expect before you contact a professional.</p>
                </div>
            </div>
            <div class="faq-list">
                <details>
                    <summary>
                        <span>What does a verified profile mean?</span>
                        <span class="faq-toggle"><i data-lucide="chevron-down" aria-hidden="true"></i></span>
                    </summary>
                    <div class="faq-answer">
                        <p>It means the professional submitted identity documents and LocalPro approved the profile. Verification helps confirm who is using the account; it is not a guarantee of workmanship, price, or future conduct. Review the profile and discuss your job before you agree.</p>
                    </div>
                </details>
                <details>
                    <summary>
                        <span>How do I agree on a price?</span>
                        <span class="faq-toggle"><i data-lucide="chevron-down" aria-hidden="true"></i></span>
                    </summary>
                    <div class="faq-answer">
                        <p>Describe the work in your request and use messages to discuss the scope, quote, materials, transport, and timing. Confirm the final amount with the professional before work begins. Indicative profile prices are starting points and may not cover every job.</p>
                    </div>
                </details>
                <details>
                    <summary>
                        <span>When should I leave a review?</span>
                        <span class="faq-toggle"><i data-lucide="chevron-down" aria-hidden="true"></i></span>
                    </summary>
                    <div class="faq-answer">
                        <p>After the professional marks the job complete, you can rate and describe your experience from the request page. Reviews should be honest, respectful, and about the work you received.</p>
                    </div>
                </details>
                <details>
                    <summary>
                        <span>What if something goes wrong?</span>
                        <span class="faq-toggle"><i data-lucide="chevron-down" aria-hidden="true"></i></span>
                    </summary>
                    <div class="faq-answer">
                        <p>Keep the conversation in your LocalPro account and contact support with the request number. The support team can review the case history and help both sides understand the next steps.</p>
                    </div>
                </details>
                <details>
                    <summary>
                        <span>Does LocalPro serve every town in the region?</span>
                        <span class="faq-toggle"><i data-lucide="chevron-down" aria-hidden="true"></i></span>
                    </summary>
                    <div class="faq-answer">
                        <p>LocalPro is starting in Ho and nearby Volta Region communities. Availability depends on which professionals have completed their profiles and been approved in your area.</p>
                    </div>
                </details>
            </div>
            <div class="faq-support-card">
                <div class="faq-support-info">
                    <span class="faq-support-icon"><i data-lucide="life-buoy" aria-hidden="true"></i></span>
                    <div>
                        <strong>Still have questions?</strong>
                        <p>Can’t find what you need? Our friendly local support team in Ho is ready to help you.</p>
                    </div>
                </div>
                <a class="faq-support-btn" href="{{ route('contact') }}">
                    <span>Contact LocalPro Support</span>
                    <i data-lucide="arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>
</main>
@include('components.footer.footer')
</body>
</html>
