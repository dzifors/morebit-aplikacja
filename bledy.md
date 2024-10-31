# BŁĄD: relacja "cregisters.creg" nie istnieje

Błąd ten może wystąpić, jeżeli tabela `cregisters.creg` nie istnieje.

W takim wypadku, należy się upewnić, czy tabela, która nas interesuje faktycznie ma taką nazwę i ewentualnie poprawić kod, lub utworzyć tabelę o takiej nazwie. Możliwe, że pomyliliśmy się przy wpisywaniu jej nazwy, albo nie zgadza się wielkość liter.

# Nie można wypisać wniosku na dzień w którym nie ma okresu zatrudnienia.

Należy upewnić się czy wybraliśmy odpowiedni przedział dat lub odpowiedniego pracownika.

# Eksport danych do Sage ERP FK -1

Według [dokumentacji](https://pomoc.symfonia.pl/data/fk/erp/2021_1_a/data/kody_bledow_importu_specjalneg.htm), oznacza to, że w pliku wejściowym są symbole (nazwa zmiennej lub pola wyjściowego) które mają zbyt długie nazwy

Aby to naprawić, należy zmodyfikować pliki które eksportujemy do programu w taki sposób, aby symbole które w nich występują, były krótsze niż 60 znaków.

# Failed to load resource: net::ERR_FAILED

Błąd ten może wystąpić, jeżeli przeglądarka nie może się połączyć z serwerem.

Należy się upewnić czy wysyłamy zapytanie do odpowiedniego serwera, czy serwer jest uruchomiony, oraz czy nazwa domeny się zgadza.
