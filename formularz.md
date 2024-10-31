# Tabela `users`

```sql
-- Aby ograniczyć szansę na wprowadzenie błędnych danych oraz zmaksymalizować spójność danych, utworzymy typ enumeracyjny,
-- który zapewni, że w kolumnach mogą się znaleźć tylko wartości, które są przez nas przewidziane.

-- Typ użytkownika: `person` - osoba fizyczna, `company` - firma
CREATE TYPE user_type_enum AS ENUM ('person', 'company')
```

## Stuktura tabeli

|     id      |   user_type    |                     user_name                      |                     company_name                      | email           |                     birth_date                     |                     nip                      | registration_date |
| :---------: | :------------: | :------------------------------------------------: | :---------------------------------------------------: | :-------------- | :------------------------------------------------: | :------------------------------------------: | :---------------: |
|   SERIAL    | user_type_enum |                    VARCHAR(50)                     |                     VARCHAR(100)                      | VARCHAR(100)    |                        DATE                        |                   CHAR(10)                   |     TIMESTAMP     |
| PRIMARY KEY |    NOT NULL    | CHECK (user_type = 'person' OR first_name IS NULL) | CHECK (user_type = 'company' OR company_name IS NULL) | UNIQUE NOT NULL | CHECK (user_type = 'person' OR birth_date IS NULL) | CHECK (user_type = 'company' OR nip IS NULL) |   DEFAULT NOW()   |

### Legenda:

|               nazwa kolumny               |
| :---------------------------------------: |
|                typ kolumny                |
| dodatkowe argumenty przy tworzeniu tabeli |

## Kwerenda tworząca tabelę według tych wytycznych

```sql
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    user_type user_type_enum NOT NULL,
    user_name VARCHAR(50) CHECK (user_type = 'person' OR first_name IS NULL),
    company_name VARCHAR(100) CHECK (user_type = 'company' OR company_name IS NULL),
    email VARCHAR(100) UNIQUE NOT NULL,
    birth_date DATE CHECK (user_type = 'person' OR birth_date IS NULL),
    nip CHAR(10) CHECK (user_type = 'company' OR nip IS NULL),
    registration_date TIMESTAMP DEFAULT NOW()
);
```

# Metody weryfikacji danych

## Przed wysłaniem do serwera

### Imię użytkownika

Przy tworzeniu tabeli ustawiliśmy limit znaków w imieniu użytkownika na `50`, dlatego po stronie klienta możemy ustawić limit znaków na `50` używając parametru `maxlength` w polu odpowiadającemu imieniu użytkownika.

```html
<input type="text" id="user_name" name="user_name" maxlength="50" />
```

### Nazwa firmy

Przy nazwie firmy, podobnie jak w przypadku imienia użytkownika, jest ustawiony limit znaków, ale tym razem na `100`, który można sprawdzić po stronie użytkownika ustawiając parametr `maxlength`.

```html
<input type="text" id="company_name" name="company_name" maxlength="100" />
```

### Adres e-mail

Można się upewnić, że użytkownik wprowadzi poprawny adres e-mail ustawiając typ pola na e-mail, jako `email`. Przy tworzeniu bazy danych, ustawiliśmy limit znaków w wysokości `100`. Można go ustawić parametrem `maxlength`.

```html
<input type="email" id="email" name="email" maxlength="100" />
```

Ustawienie parametru `type` na `email`, powoduje, że przeglądarka sprawdza czy wpisana wartość ma strukturę adresu e-mail i wyświetla u użytkownika odpowiedni komunikat jeżeli takiej struktury nie posiada.

### Data urodzenia

Do wybrania daty urodzenia, wystarczy ustawić typ pola które za nią odpowiada jako `date`, co zapewni użytkownikowi wbudowany kalendarz, który od razu wprowadzi datę w odpowiednim formacie.

```html
<input type="date" id="birth_date" name="birth_date" />
```

### Numer NIP

Numer NIP ma ustawiony limit znaków na `10`. Używamy parametru `maxlength`.

```html
<input type="text" id="nip" name="nip" maxlength="10" />
```

## Po wysłaniu do serwera

Większość danych została zweryfikowana już przed wysłaniem do serwera, dlatego tutaj możemy sprawdzić dane pod względem poprawności

### Adres e-mail

Po stronie przeglądarki sprawdziliśmy czy użytkownik podał adres e-mail w poprawnym formacie, a żeby się upewnić czy podał e-mail do którego ma dostęp, możemy mu wysłać wiadomość e-mail z prośbą o potwierdzenie

### Numer NIP

Sprawdziliśmy już czy podany numer NIP ma odpowiednią długość - nie sprawdziliśmy natomiast, czy składa się z samych cyfr. Można tego dokonać sprawdzając, czy podany NIP pasuje do regexa `/^\d+$/`

```php
<?php
if (preg_match("/^\d+$/", $nip))
{
    // NIP składa się z samych cyfr, kontynuuj
} else {
    // NIP nie składa się z samych cyfr, wyświetl komunikat użytkownikowi
}
?>
```

---

W ten sposób sprawdziliśmy dane przed próbą wprowadzenia do bazy danych. Upewniliśmy się, że nie dochodzi do redundancji danych poprzez ustawienie parametru `UNIQUE` w polach w bazie danych, nie dochodzi do błędów w danych ponieważ weryfikujemy, czy dane są poprawne i spójne.
