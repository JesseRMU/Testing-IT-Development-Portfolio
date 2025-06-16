<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# User stories

## User story User story 7 – Grafiek exporteren naar afbeelding
#### Gemaakt door: Korben de vos

Als adviseur bij Rijkswaterstaat moet ik al mijn beslissingen verantwoorden doormiddel van data, daarom is het belangrijk dat ik mijn visuele inzichten kan download als afbeelding. Bestanden waar ik veel gebruik van maak zijn: png, pdf, jpg en svg (Zie appendix 2). Deze moeten dus zeker aanwezig zijn.

### Acceptance Criteria 

#### Main Flow 
- Given: De adviseur maakt gebruik van het dashboard en ziet een bruikbare grafiek die hij wil gebruiken in zijn presentatie. 
- When: Rechtsboven van de grafiek drukt hij op het download icoontje. Hij kiest het bestands type 
- Then: Download hij de grafiek als gekozen bestand. 

#### Alternate Flow 


#### Exception Flow 
- Given: De adviseur maakt gebruik van het dashboard en ziet een bruikbare grafiek die hij wil gebruiken in zijn presentatie. 
- When Rechtsboven van de grafiek drukt hij op het download icoontje. Hij kiest het bestands type. Hij heeft geen opslagruimte meer. 
- Then: Hij krijgt een melding dat zijn opslag vol is en dat de download geannuleerd is. 

### Testplan
- Test of de exporteer opties gerendered worden in de grafiek widget (unit) - als dit niet werkt kan de gebruiker niet exporteren.
-- Given: Een windget met de titel "Testgrafiek met Graph.js" is gerendered
-- When: De opties van de bijbehoordende widget is uitgeklapt
-- Then: De opties zijn zichtbaar voor png, pdf, jpg, en svg.

- Test of de exporteer opties gerendered worden in niet-grafiek widgets (unit) - als het fout gaat is er meer clutter en kunnen er errors ontstaan als er op gedrukt wordt.
-- Given: Een widget met een andere titel dan "Testgrafiek met Graph.js" wordt weergegeven
-- When: De opties van de bijbehoordende widget is uitgeklapt
-- Then: De opties voor png, pdf, jpg, en svg zijn niet zichtbaar.

- Test of de grafieken data van de database kan gebruiken met geldige group_by_time paramenter (feature) - anders zijn de grafieken leeg of kunnen er errors ontstaan.
-- Given: Een geldige group_by_time parameter wordt opgestuurd.
-- When: Het endpoint van de grafiekgegevens wordt opgeroepen.
-- Then: Het geeft de response data terug met de juiste structuur, inclusief labels en datasets.

- Test of er een foutmelding getoond wordt met foute data (feature) - anders is de data fout ofkunnen er errors ontstaan.
-- Given: Er wordt een ongeldige group_by_time parameter meegegeven in een request
-- When: De grafiek-data endpoint wordt aangeroepen
-- Then: Het Ggeft de response een 422 error terug

- ~~Test of het juiste gedownload wordt (voor iedere optie) (feature) - om te kijken of het downloaden zelf werkt~~ is niet mogelijk met laravel testing
-- ~~Given: Er is een grafiek gerendered.~~
-- ~~When: De gebruiker druk op een export optie~~
-- ~~Then: Een bestand wordt gedownload~~

#### Test resultaten
![Test resultaten](/resources/images/tests/GraphExortBladeTest.PNG "Task tests")
![Test resultaten](/resources/images/tests/GraphDataTest.PNG "Task tests")

### Evaluatie
Met deze tests kunnen we snel zien of de export opties werken en niet gerenderd worden bij andere widgets.
Ook weten we of het een foutmelding geeft als de grafiek foute data heeft, en dat het niets doet als het correct is.
The laatste test kon niet gemaakt worden omdat dat op browser niveau is, en dus niet door laravel getest kan worden.
Ook kan er niet met svg gewerkt worden, de browser is een canvas en daardoor kan svg niet. 
Het kan daardoor ook niet testen wat er gebeurd als de opslag vol zit, maar de browser stopt de download dan toch.
Ook kan er zo niet door de inhoud van de afbeeldingen zelf gekeken worden.
We kunnen uit deze tests dus wel de frontend opties testen, en of het genereren van de grafiek zelf naar behoren werkt.

### Conclusie en Acties
- Er waren nog wat fouten voor SQLite, deze zijn verwerkt:
- FIELD uit EvenementController gehaald voor SQLite en testing
- "day" uit validatie in GraphController gehaald voor SQLite en testing
- Deze tests hebben de kwaliteit van de code verbeterd en wordt automatisch gedraaid wanneer het gepushed, en verzekerd daarmee toekomstige kwaliteit.

## User story User story 9 – Meldingen hoog ligplaatsgebruik 
#### Gemaakt door: Korben de vos

Als data-analist wil ik op de hoogte gebracht worden als het ligplaatsgebruik van een object heel hoog is zodat ik snel zie bij welke objecten het vaak heel druk is. Dit moet voor iedere dag getoond worden waar de ligplaatsbezetting boven het percentage is. Het percentage wordt later bepaald. 

### Acceptance Criteria 

#### Main Flow 
- Given: De data-analist is op het dashboard. 
- When: Een object heeft een hoog ligplaatsgebruik. 
- Then: Het dashboard toont een melding. 
- Then: De data-analist drukt op de melding voor meer details. 

#### Alternate Flow 

#### Exception Flow 
- Given: De data-analist is op het dashboard. 
- When: Er is nergens hoog ligplaatsgebruik. 
- Then: Er wordt geen melding weergegeven. 

### Testplan
- Toon waarschuwingen wanneer er meer evenementen dan steigers zijn systeem op code level (unit) - om te zien of de service werkt en waarschuwingen laat zien
-- Given: Er zijn meer evenementen dan steigers op een dag bij een wachthaven
-- When: De gebruiker is op het dashboard pagina
-- Then: Er worden waarschuwingen getoond

- Toon geen waarschuwingen wanneer er minder evenementen dan steigers zijn op code level (unit) - om te zien of de service werkt en gana waarschuwingen laat zien
-- Given: Er zijn minder evenementen dan steigers op een dag bij een wachthaven
-- When: De gebruiker is op het dashboard pagina
-- Then: Er worden geen waarschuwingen getoond

- Toon waarschuwingen wanneer er meer evenementen dan steigers zijn met factories en database (feature) - om te zien of alle lagen werken en er waarschuwingen worden getoont
-- Given: Er zijn meer evenementen dan steigers op een dag bij een wachthaven in de database
-- When: De gebruiker is op het dashboard pagina
-- Then: Er worden waarschuwingen getoond

- Toon geen waarschuwingen wanneer er minder evenementen dan steigers zijn met factories en database (feature) - om te zien of alle lagen werken en er geen waarschuwingen worden getoont
-- Given: Er zijn minder evenementen dan steigers op een dag bij een wachthaven in de database
-- When: De gebruiker is op het dashboard pagina
-- Then: Er worden geen waarschuwingen getoond

- ~~Test of de waarschuwingen gerenderd worden - om te zien of de waarschuwing überhaubt tevoorschijn komen~~ Sinds een gedeelte toch niet getest kan worden (meer details tonen met js) en het belangrijkste al gedekt wordt door eerdere tests is deze gelaten
-- ~~Given: Er zijn meer evenementen dan steigers op een dag bij een wachthaven~~
-- ~~When: De gebruiker is op het dashboard pagina~~
-- ~~Then: Er worden waarschuwingen getoond~~

#### Test resultaten
![Test resultaten](/resources/images/tests/WarningServiceTest.PNG "Task tests")
![Test resultaten](/resources/images/tests/GetWarningsTest.PNG "Task tests")

### Evaluatie
Deze test kijken of de logica van de code goed werkt.
Denk hierbij van het verwerken van data en het genereren van waarschuwingen. 
Mocht er een fout zijn bij het generen van waarschuwingen pakken deze tests dat op.
Bijvoorbeeld als er wat mis gaat op het database niveau, data verwerkings niveau, of waarschuwing genereer niveau.
Deze tests testen alleen de logica, met en zonder database.
Je kan dus wel met deze tests zien het waarschuwingssysteem werkt, maar voor de front-end met iemand er zelf naar kijken.

### Conclusie en Acties
- De code is deels opgesplit naar een service, wat het meer overzichtbaar maakt.
- Factories zijn toegevoegd
- Deze tests hebben de kwaliteit van de code verbeterd en wordt automatisch gedraaid wanneer het gepushed, en verzekerd daarmee toekomstige kwaliteit.
