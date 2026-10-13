# speelkast
Inloggen met username:Test password:123
De spellen staan onder de folder spellen. 

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
upload:
    login
    logout
    index
    toevoegen
    export
    speelkast
    avondlijst


