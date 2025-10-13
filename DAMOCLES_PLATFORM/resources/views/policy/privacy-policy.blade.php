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
            <p class="font-bold mb-2 text-xl pb-3 justify-start">@lang('welcome.privacyPolicy')</p>

            <div class="my-2">
                <p class="mb-2">Benvenuto nella pagina della Privacy Policy di DAMOCLES. La tua
                    privacy è importante per noi, e ci impegniamo a proteggere i tuoi dati personali in conformità con
                    il
                    Regolamento Generale sulla Protezione dei Dati (GDPR).
                </p>
            </div>

            <div class="my-2">
                <p class="mb-2">Informativa riguardante il trattamento dei dati personali redatta e fornita da
                    DAMOCLES in qualità di Titolare del trattamento, ai sensi dell’art. 13 del D.Lgs.
                    196/2003 "Codice in materia di protezione dei dati personali" (di seguito anche "Codice") e ai
                    sensi degli artt. 13 e 14 del Regolamento UE 2016/679 in materia di protezione dei dati
                    personali delle persone fisiche (di seguito anche "Regolamento" o "GDPR"), agli Utenti che
                    consultano il sito <a href="" target="_blank"></a>
                    (di seguito anche solo il "Sito").</p>
                <p class="mb-2">L’informativa che segue è resa per il solo sito <a href="" target="_blank"></a>
                    e non
                    anche per altri siti web eventualmente consultati dall’Utente tramite link. Il
                    Titolare raccoglierà e tratteranno le tipologie di dati di seguito elencate in
                    conformità con quanto disposto dal Codice e dal Regolamento.</p>
            </div>

            <div class="my-2">
                <p class="font-bold mb-2">1. Titolare del Trattamento dei Dati e informazioni di contatto</p>
                <p class="mb-2">DAMOCLES **INSERIRE DATI** di Mario Rossi, con sede legale a Bari (Ba), in
                    via mario rossi, telefono: 123 123 1234 - 123 123 1234, pec: <a href="mailto:example@pec.it"
                        target="_blank">example@pec.it</a> ed e-mail: <a href="mailto:example@gmmail.com"
                        target="_blank">example@gmail.com</a>,
                    P. IVA: 123412341234.</p>
            </div>

            <div class="my-2">
                <p class="font-bold mb-2">2. Modalità di Trattamento</p>
                <p class="mb-2">Il Titolare tratta i dati personali forniti e/o raccolti dagli utenti attraver il
                    form di registrazione presente sul sito web, compilato direttamente dall'utente, con
                    strumenti analogici, informatici e/o telematici, adottando le adeguate misure di
                    sicurezza volte ad impedire l’accesso abusivo ai sistemi e, quindi, la loro
                    divulgazione, modifica o distruzione non autorizzate.</p>
                <p class="mb-2">I dati personali sono trattati anche in forma aggregata, con modalità
                    organizzative e con logiche strettamente idonee alle finalità indicate nella presente
                    informativa.</p>
            </div>

            <div class="my-2">
                <p class="font-bold mb-2">3. Tipologie di Dati raccolti e finalità</p>
                <p class="mb-2">a) Dati di contatto</p>
                <p class="mb-2">Tali dati sono chiesti all’Utente in fase di compilazione del modulo di
                    richiesta informazioni presente sul Sito e comprendono: nome, cognome, data di nascita,
                    e indirizzo e-mail.</p>
                <p class="mb-2">Detti dati saranno e potranno essere oggetto di trattamento da parte del
                    Titolare per adempiere alle specifiche richieste dell’Utente.
                    I dati verranno utilizzati per i seguenti scopi:
                </p>
                <p class="mb-2">Funzionalità 1</p>
                <p class="mb-2">Funzionalità 2</p>
                <p class="mb-2">Funzionalità 3</p>
                <p class="mb-2">Nota: I dati non vengono utilizzati per scopi di marketing né condivisi con terze
                    parti.
                </p>

                <!-- <p class="mb-2">Previo esplicito consenso dell’utente, i suoi dati potranno essere
                        trattati per l’invio di newsletter e di informazioni commerciali.</p> -->

                <p class="mb-2">b) Conferimento dei dati</p>
                <p class="mb-2">Il conferimento dei Dati di contatto, di cui alla sez. III a) della
                    presente informativa, ha natura obbligatoria ed il loro mancato conferimento, anche
                    parziale, determinerà l’impossibilità per il Titolare di comunicare all’utente le
                    informazioni richieste per i servizi presenti sulla piattaforma.</p>
                <p class="mb-2">L’Utente si assume la responsabilità dei Dati Personali di terzi
                    comunicati o condivisi mediante il sito <a href="" target="_blank"></a> e garantisce di avere
                    il diritto di
                    comunicarli o diffonderli, liberando i Titolari da qualsiasi responsabilità verso terzi.
                </p>
            </div>

            <div class="my-2">
                <p class="font-bold mb-2">4. Luogo e durata del trattamento dei Dati raccolti</p>
                <p class="mb-2">a) Luogo</p>
                <p class="mb-2">I Dati sono trattati presso le sedi operative del Titolare e dei
                    Responsabili del trattamento.</p>
                <p class="mb-2">Qualora i dati personali siano trasferiti a un paese terzo o a
                    un’organizzazione internazionale, l’interessato sarà informato dell’esistenza di
                    garanzie adeguate ai sensi dell’articolo 46 del "Regolamento" relative al trasferimento.
                </p>
                <p class="mb-2">Per ottenere ulteriori informazioni è possibile contattare il Titolare.
                </p>
                <p class="mb-2">b) Durata del trattamento e della conservazione</p>
                <p class="mb-2">I Dati sono trattati per il tempo necessario allo svolgimento del servizio
                    richiesto dall’Utente e questi può sempre chiedere l’interruzione del Trattamento o la
                    cancellazione, l’aggiornamento e la modifica dei Dati.</p>
                <p class="mb-2">Ad ogni modo, i dati personali e particolari saranno trattati:</p>
                <p class="mb-2">Per il tempo strettamente necessario all’evasione delle sue richieste;</p>
                <p class="mb-2">Il Titolare conserverà i dati personali dell’utente per i 10 anni
                    successivi alla conclusione del trattamento per l’eventuale esercizio del diritto di
                    difesa.</p>
            </div>

            <div class="my-2">
                <p class="font-bold mb-2">5. Cookie Policy</p>
                <p class="mb-2">a) Informazioni sui Cookie</p>
                <p class="mb-2">Un cookie (dall’inglese, letteralmente, "biscotto") è un piccolo e leggero
                    file testuale che viene generato dai servizi web al fine di memorizzare le preferenze,
                    le attività ed i gusti degli utenti. Il cookie creato da un servizio potrà essere letto
                    e modificato dallo stesso al fine di caratterizzare al meglio la propria utenza e,
                    soprattutto, di riconoscere l’utente al suo ritorno sul sito.</p>
                <p class="mb-2">Dunque, nel cookie possono essere memorizzate diverse informazioni per
                    disparate finalità, ma solo se l’utente ha abilitato l’installazione dei cookie dalle
                    preferenze del proprio browser.</p>
                <p class="mb-2">V’è da precisare che i cookie non sono e non possono essere pericolosi nel
                    senso comune del termine: infatti, non possono veicolare in alcun modo virus o malware
                    di altro genere. Possono, invece, essere utilizzati per tracciare il comportamento degli
                    utenti sui siti web che adottano alcuni servizi.</p>
                <p class="mb-2">In generale i cookie possono essere disattivati completamente dalle
                    impostazioni del proprio browser in qualsiasi momento. Per maggiori informazioni, si
                    consiglia di leggere le pagine di aiuto e supporto relative ad esso fornite dagli
                    sviluppatori degli stessi.</p>
                <!--
                    <p class="mb-2">b) Servizi di terze parti che utilizzano i cookie</p>
                    <p class="mb-2">Google Analytics</p>
                    <p class="mb-2">Google Analytics è un servizio terzo di analisi web fornito da Google Inc.
                        Google utilizza i dati personali raccolti attraverso i propri script al fine di
                        tracciare ed esaminare la navigazione su questo sito ed i servizi da esso offerti,
                        compilare report e condividerli con gli altri servizi terzi sviluppati da Google.</p>
                    <p class="mb-2">Google utilizza i dati personali per contestualizzare e personalizzare gli
                        annunci del proprio network pubblicitario composto da Adsense e Adwords.</p>
                    <p class="mb-2">Dati personali raccolti: Cookie e dati di navigazione sul sito.</p>
                    <p class="mb-2">Luogo del trattamento: USA</p>
                    <p class="mb-2">Privacy Policy: <a href="http://www.google.com/intl/it/policies/privacy/"
                            target="_blank">http://www.google.com/intl/it/policies/privacy/</a></p>
                    <p class="mb-2">È possibile esercitare il diritto di opt out da Google Analytics da qui:
                        <a href="http://tools.google.com/dlpage/gaoptout?hl=it"
                            target="_blank">http://tools.google.com/dlpage/gaoptout?hl=it</a>
                    </p>
                    <p class="mb-2">Google LLC aderisce al Privacy Shield.</p>
                    <p class="mb-2">Pixel di Facebook Ads</p>
                    <p class="mb-2">Fornito da Facebook, Inc.</p>
                    <p class="mb-2">Il pixel di Facebook Ads serve a monitorare le conversioni delle
                        inserzioni pubblicate dal Titolare del sito su Facebook.</p>
                    <p class="mb-2">Il servizio collega i dati provenienti dalla rete di annunci pubblicitari
                        di Facebook con le azioni compiute all’interno di questo sito.</p>
                    <p class="mb-2">Dati Personali raccolti: Cookie e Dati di utilizzo.</p>
                    <p class="mb-2">Luogo del trattamento: USA.</p>
                    <p class="mb-2">Facebook, Inc. aderisce al Privacy Shield.</p>
                    <p class="mb-2">Privacy Policy: <a href="https://www.facebook.com/privacy/explanation"
                            target="_blank">https://www.facebook.com/privacy/explanation</a></p>
                    -->
            </div>

            <div class="my-2">
                <p class="font-bold mb-2">6. Diritti degli Interessati</p>
                <p class="mb-2">Gli utenti, in qualità di interessati, potranno esercitare i diritti
                    riconosciuti loro dal GDPR, segnatamente:</p>

                <div class="mb-2">
                    <p>1. Il diritto d’accesso</p>
                    <p>2. Il diritto di rettifica dei dati</p>
                    <p>3. Il diritto alla cancellazione e all’oblio</p>
                    <p>4. Il diritto alla limitazione del trattamento</p>
                    <p>5. Il diritto di opposizione al trattamento</p>
                    <p>6. Il diritto alla portabilità dei dati</p>
                </div>

                <p class="mb-2">I diritti potranno essere esercitati presentando una istanza informale al
                    Titolare del trattamento, il quale risponderà entro trenta (30) giorni dalla sua
                    ricezione. Detto termine può essere prorogato di ulteriori sessanta (60) giorni se
                    l’adempimento della richiesta è particolarmente gravoso per il Titolare.</p>
                <p class="mb-2">Il Titolare informa gli utenti che, nel caso in cui non sia data una
                    risposta nei termini indicati, oppure questa non soddisfi loro, o, ancora, questi
                    ritengano che vi sia stata una violazione dei loro diritti, potranno proporre reclamo al
                    Garante per la Protezione dei Dati Personali secondo le modalità indicate sul sito
                    internet del Garante, accessibile all’indirizzo: <a href="http://www.gpdp.it"
                        target="_blank">http://www.gpdp.it</a>.</p>
                <p class="mb-2">L’esercizio dei diritti è gratuito, a meno che il Titolare non debba
                    sopportare costi troppo onerosi.</p>
            </div>

            <div class="my-2">
                <p class="font-bold mb-2">7. Modifiche a questa privacy policy</p>
                <p class="mb-2">Il Titolare del trattamento si riservano il diritto di apportare
                    qualsivoglia modifica alla presente informativa estesa dandone pubblicità su questa
                    pagina.</p>
                <p class="mb-2">In fondo alla presente sarà apposta la data di ultima modifica per
                    consentire il tracciamento delle modificazioni stesse. Una copia di ogni versione della
                    presente informativa è a disposizione degli Interessati presso la sede legale del
                    Titolare.</p>
                <p class="mb-2">Nel caso in cui l’utente non accetti le modifiche apportate, può chiedere
                    al Titolare del trattamento di rimuovere i propri dati personali. Salvo quanto
                    diversamente specificato, la precedente informativa sulla privacy e sui cookie
                    continuerà ad essere applicata ai dati personali sino a quel momento raccolti.</p>
            </div>
        </section>

    </main>

    @include('layouts.footer')
</body>

</html>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
