<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')

    <title>{{ config('APP.NAME', 'DAMOCLES') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
</head>

<body class="flex flex-col justify-between font-sans antialiased min-h-screen bg-sky-100 text-sky-900">

    @include('layouts.welcome-navigation')

    <main class="flex flex-col items-center justify-center max-w-screen-xl mx-auto min-h-screen py-4 md:py-0">
        <!-- Sections -->
        <div class="flex flex-col gap-8 md:gap-16 text-center items-center">
            <!-- Hero Section -->
            <section class="hero">
                <div>
                    <p class="text-5xl font-extrabold">DAMOCLES</p>
                    <p class="text-3xl">Detection And Mitigation Of Cyber attacks that exploit human vuLnerabilitiES</p>
                    <p class="text-3xl">Leverage Human Vulnerability Assessment & Mitigation</p>
                </div>
            </section>

            <!-- About Section -->
            <section id="about">
                <p class="text-2xl font-bold">What is DAMOCLES?</p>
                <p class="text-xl">
                    DAMOCLES is a cybersecurity research project aimed at protecting Italian Public Administrations from
                    security
                    breaches caused by human error. Our innovative framework integrates Human Vulnerability Assessment
                    (HVA)
                    and Human Vulnerability Mitigation (HVM) techniques to prevent cyber incidents.
                </p>
            </section>

            <!-- Features Section -->
            <section id="features">
                <p class="text-2xl font-bold">Key Features</p>
                <div class="flex flex-col md:flex-row mt-4 gap-8">
                    <div class="w-full md:w-1/3 flex">
                        <div class="feature-box p-8 shadow-md bg-white rounded-md flex flex-col h-full">
                            <p class="text-xl font-semibold pb-2">Human Vulnerability Assessment</p>
                            <p class="text-xl flex-grow">We identify possible cyber security risks caused by human error
                                through
                                psychometric questionnaires, ethical phishing campaigns, and simulations of user
                                behaviour during
                                various cyber attacks.
                            </p>
                        </div>
                    </div>

                    <div class="w-full md:w-1/3 flex">
                        <div class="feature-box p-8 shadow-md bg-white rounded-md flex flex-col h-full">
                            <p class="text-xl font-semibold pb-2">Human Vulnerability Mitigation</p>
                            <p class="text-xl flex-grow">Based on the risks identified in the assessment phase, we
                                propose
                                solutions to mitigate possible user misbehaviour through customised user profile
                                training campaigns.
                                Such training campaigns can be delivered as readable texts, podcasts, video tutorials,
                                role-playing games.
                            </p>
                        </div>
                    </div>

                    <div class="w-full md:w-1/3 flex">
                        <div class="feature-box p-8 shadow-md bg-white rounded-md flex flex-col h-full">
                            <p class="text-xl font-semibold pb-2">Fully customisable</p>
                            <p class="text-xl flex-grow">Considering the many different user requirements, the platform
                                can be easily
                                customised to the specific usage needs of the public administration.
                            </p>
                        </div>
                    </div>
                </div>

            </section>
        </div>
        <!-- Login Section -->
        {{-- <section id="login">
            @include('auth.login')
        </section> --}}
    </main>

    @include('layouts.footer')
</body>

</html>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
