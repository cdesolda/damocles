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

    <main class="flex flex-col h-auto w-full max-w-screen-xl justify-center mx-auto">
        <section class="flex flex-col min-h-screen p-4 sm:p-6">
            <p class="font-semibold text-xl pb-3 justify-start">@lang('welcome.cookiePolicy')</p>

            <div class="my-2">
                <p class="mb-2">Questa politica sui cookie è stata aggiornata l’ultima volta il 17
                    Febbraio 2025 e si applica ai cittadini e ai residenti permanenti legali dello Spazio
                    Economico Europeo e della Svizzera.</p>
            </div>

            <div class="my-2">
                <h6 class="font-bold mb-2">1. Introduzione</h6>
                <p class="mb-2">Il nostro sito web, <a href="" target="_blank">**SITO WEB**</a> (di
                    seguito: "il sito web") utilizza i
                    cookie e altre tecnologie correlate (per comodità tutte le tecnologie sono definite
                    "cookie"). I cookie vengono anche inseriti da terze parti che abbiamo ingaggiato. Nel
                    documento sottostante ti informiamo sull’uso dei cookie sul nostro sito web.</p>
            </div>

            <div class="my-2">
                <h6 class="font-bold mb-2">2. Cosa sono i cookie?</h6>
                <p class="mb-2">I cookie sono dei semplici file spediti assieme alle pagine di questo sito
                    e salvati dal tuo browser sul disco rigido del tuo computer o altri dispositivi. Le
                    informazioni raccolte in essi possono venire rispediti ai nostri server oppure ai server
                    di terze parti durante la prossima visita.</p>
            </div>

            <div class="my-2">
                <h6 class="font-bold mb-2">3. Cosa sono gli script?</h6>
                <p class="mb-2">Uno script è un pezzo di codice usato per far funzionare correttamente ed
                    interattivamente il nostro sito. Questo codice viene eseguito sui nostri server o sul
                    tuo dispositivo.</p>
            </div>

            <div class="my-2">
                <h6 class="font-bold mb-2">4. Cos’è un web beacon?</h6>
                <p class="mb-2">Un web beacon (o pixel tag) è un piccolo, invisibile pezzo di testo o
                    immagine su un sito che viene usato per monitorare il traffico di un sito web. Per fare
                    questo, diversi dati su di te vengono conservati utilizzando dei web beacon.</p>
            </div>

            <div class="my-2">
                <h6 class="font-bold mb-2">5. Cookie</h6>
                <div class="ml-4 my-2">
                    <h6 class="font-bold mb-2">5.1 Cookie tecnici o funzionali</h6>
                    <p class="mb-2">Alcuni cookie assicurano il corretto funzionamento del sito e che le tue
                        preferenze utente rimangano valide. Piazzando cookie funzionali, rendiamo più facile per
                        te visitare il nostro sito web. In questo modo non devi inserire ripetutamente le stesse
                        informazioni quando visiti il nostro sito web, per esempio, l’oggetto rimane nel tuo
                        carrello finché non hai pagato. Possiamo piazzare questi cookie senza il tuo consenso.
                    </p>
                </div>
            </div>

            <div class="my-2">
                <h6 class="font-bold mb-2">6. Cookie piazzati</h6>
                <table class="text-primary-500 mt-4 border border-[#075985]">
                    <thead>
                        <tr>
                            <th class="border border-[#075985] p-2">Nome del Cookie</th>
                            <th class="border border-[#075985] p-2">Scopo</th>
                            <th class="border border-[#075985] p-2">Durata</th>
                            <th class="border border-[#075985] p-2">Categoria</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-[#075985] p-2">XSRF-TOKEN</td>
                            <td class="border border-[#075985] p-2">Protezione da attacchi CSRF (Cross-Site Request Forgery),
                                garantendo la sicurezza delle richieste inviate dagli utenti.</td>
                            <td class="border border-[#075985] p-2">Cookie di sessione.</td>
                            <td class="border border-[#075985] p-2">Tecnico</td>
                        </tr>
                        <tr>
                            <td class="border border-[#075985] p-2">damocles_session</td>
                            <td class="border border-[#075985] p-2">Memorizzare informazioni della sessione attiva dell'utente
                                per garantire l'accesso e il funzionamento della piattaforma.</td>
                            <td class="border border-[#075985] p-2">Cookie di sessione.</td>
                            <td class="border border-[#075985] p-2">Tecnico</td>
                        </tr>
                        <tr>
                            <td class="border border-[#075985] p-2">laravel_cookie_consent</td>
                            <td class="border border-[#075985] p-2">Ricordare la scelta dell'utente sul consenso della policy
                                dei cookie e della privacy.</td>
                            <td class="border border-[#075985] p-2">Persistente (scade automaticamente dopo un periodo di
                                tempo).</td>
                            <td class="border border-[#075985] p-2">Tecnico</td>
                        </tr>
                        <tr>
                            <td class="border border-[#075985] p-2">remember_web</td>
                            <td class="border border-[#075985] p-2">Ricordare l'utente autenticato per facilitare l'accesso
                                senza dover effettuare nuovamente il login.</td>
                            <td class="border border-[#075985] p-2">Persistente (scade automaticamente dopo un periodo di
                                tempo).</td>
                            <td class="border border-[#075985] p-2">Tecnico</td>
                        </tr>
                    </tbody>
                </table>

                <div>
                    <p class="font-bold mb-2">Gestione dei cookie</p>
                    <p>Poiché utilizziamo esclusivamente cookie tecnici, non è necessario il consenso esplicito
                        degli utenti per l'installazione di questi cookie. Tuttavia, è possibile disattivare i
                        cookie modificando le impostazioni del browser. Si noti che disabilitare i cookie tecnici
                        potrebbe compromettere il corretto funzionamento del sito.</p>
                </div>
            </div>

            <div class="my-2">
                <h6 class="font-bold mb-2">7. Consenti</h6>
                <p class="mb-2">Quando visiti il sito web per la prima volta, noi mostreremo un popup con
                    una spiegazione dei cookie. Appena clicchi su "Salva preferenze", dai il permesso a noi
                    di usare le categorie di cookie e plugin come descritto in questa dichiarazione relativa
                    ai popup e cookie. Puoi disabilitare i cookie attraverso il tuo browser, ma prendi in
                    considerazione, che il nostro sito web potrebbe non funzionare più correttamente.</p>
                <div class="ml-4">

                    <h6 class="font-bold mb-2">7.1 Gestisci le tue impostazioni di consenso</h6>
                    <div class="mb-2">
                        <p class="font-semibold">Funzionale: Sempre attivo</p>

                        <p>L'archiviazione tecnica o
                            l'accesso sono strettamente necessari
                            al fine legittimo di consentire l'uso di un servizio specifico
                            esplicitamente richiesto dall'abbonato o dall'utente, o al solo
                            scopo di effettuare la trasmissione di una comunicazione su una
                            rete di comunicazione elettronica.</p>

                    </div>
                    <p>Gli utenti possono gestire o eliminare i cookie utilizzando le impostazioni del proprio browser.
                        Di
                        seguito sono riportati i link alle istruzioni per i browser più comuni:

                        <a href="https://support.google.com/chrome/answer/95647" target="_blank">Google Chrome,</a>
                        <a href="https://support.mozilla.org/en-US/kb/enable-and-disable-cookies-website-preferences"
                            target="_blank">Mozilla Firefox,</a>
                        <a href="https://support.apple.com/guide/safari/manage-cookies-and-website-data-sfri11471/mac"
                            target="_blank">Safari,</a>
                        <a href="https://support.microsoft.com/en-us/microsoft-edge/delete-cookies-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09"
                            target="_blank">Microsoft Edge.</a>
                </div>
            </div>

            <div class="my-2">
                <h6 class="font-bold mb-2">8. I tuoi diritti con rispetto ai dati personali</h6>
                <p class="mb-2">Hai i seguenti diritti relativi ai tuoi dati personali:
                </p>
                <ul>
                    <li>Hai il diritto di sapere quando i tuoi dati personali sono necessari, cosa succede
                        ad essi, quanto a lungo verranno mantenuti.</li>
                    <li>Diritto di accesso: hai il diritto ad accedere ai tuoi dati personali dei quali
                        siamo a conoscenza.</li>
                    <li>Diritto di rettifica: hai il diritto di completare, correggere, cancellare o
                        bloccare i tuoi dati personali quando lo desideri.</li>
                    <li>Se ci darai il consenso per elaborare i tuoi dati, hai il diritto di revocare questo
                        consenso e di eliminare i tuoi dati personali.</li>
                    <li>Diritto di trasferire i tuoi dati: hai il diritto di richiedere tutti i tuoi dati
                        dal controllore e trasferirli tutti quanti ad un altro controllore.</li>
                    <li>Diritto di opposizione: hai il diritto di opporti al trattamento dei tuoi dati. Noi
                        rispetteremo questa scelta, a meno che non ci siano delle basi valide per trattarli.
                    </li>
                    <ul>
                        <p></p>
                        <p class="mb-2">Per esercitare questi diritti, non esitate a contattarci. Si
                            prega di fare riferimento ai dettagli di contatto in fondo a questa Cookie
                            Policy. Se hai un reclamo su come gestiamo i tuoi dati, vorremmo sentirti, ma
                            hai anche il diritto di presentare un reclamo all’autorità di vigilanza
                            (l’Autorità per la Protezione dei Dati).</p>
                    </ul>
                </ul>
            </div>

            <div class="my-2">
                <h6 class="font-bold mb-2">9. Abilitare/disabilitare e cancellazione dei cookie</h6>
                <p class="mb-2">Puoi usare il tuo browser per cancellare automaticamente o manualmente i
                    cookie. È anche possibile specificare che determinati cookie non possono essere
                    piazzati. Un’altra opzione è quella di modificare le impostazioni del tuo browser
                    internet in modo da ricevere un messaggio ogni volta che viene inserito un cookie. Per
                    ulteriori informazioni su queste opzioni, consultare le istruzioni nella sezione Guida
                    del tuo browser.</p>
                <p class="mb-2">Ricorda che il nostro sito potrebbe non funzionare correttamente se tutti
                    i cookie sono disabilitati. Se cancelli i cookie nel tuo browser, saranno riposti
                    nuovamente dopo il tuo permesso quando visiterai nuovamente il nostro sito.</p>
            </div>

            <div class="my-2">
                <h6 class="font-bold mb-2">10. Dettagli contatti</h6>
                <p class="mb-2">Per domande e/o commenti riguardo la Cookie Policy e questa
                    dichiarazione, per favore contattaci usando i seguenti dati di contatto:</p>
                <p class="mb-2">
                    **INSERIRE DATI**
                    DAMOCLES <br>
                    Via mario rossi 8 - 70125 - Bari (Bari, Italia)<br>
                    Sito web: <a href="" target="_blank"></a><br>
                    PEC: <a href="mailto:example@pec.it" target="_blank">example@pec.it</a><br>
                    Email: <a href="mailto:example@gmail.com" target="_blank">example@gmail.com</a>
                </p>
                <p class="mb-2">Questa politica sui cookie è stata aggiornata il 17 Febbraio 2025</p>
            </div>
        </section>
    </main>

    @include('layouts.footer')
</body>

</html>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
