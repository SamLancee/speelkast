# speelkast
Inloggen met username:Test password:123
De spellen staan onder de folder spellen. Alle nieuwe toegevoegde spellen komen bij upload te staan. 

Plan van aanpak:
Eerst in folder lijst met spellen
Starten met frontend: inlog en weergave van spellen

Opzet voor casus
interfaces:
    FotoInterface
    Opslaginterface
classes:
    KastItem abstracte class
    Bordspel subclass
    Uitbreiding subclass
    BestandOpslag (gebruikt opslaginterface)
    ItemRepository (database query via DI)
    UserRepository (Login via DI)
views:
    login_view
    kast_view
    toevoegen_view

login
logout
index
toevoegen
export
speelkast
avondlijst

Uitleg JS & server validatie: wanneer er alleen gebruik gemaakt wordt van JS validatie betekent het dat bij de client side alles gecheckt wordt maar bij de server side niks gebeurd. Dit is niet voldoende om je via de client side veel dingen zelf kan uitzetten via de browser of kan veranderen. De client side checks zijn voor het gemak van de gebruiker en niet zo zeer voor de echte beveiliging van de code. Hiervoor is het echt nodig om server validatie toe te voegen. 


