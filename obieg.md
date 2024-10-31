# Aktorzy uczestniczący w procesie obiegu pisma wychodzącego

1. Autor pisma - przygotowuje pismo wychodzące
2. Kierownik - akceptuje pismo
3. Zastępca Kierownika - pełni funkcję Kierownika podczas nieobecności Kierownika
4. Dyrektor - ostatecznie zatwierdza pismo
5. Zastępca Dyrektora - pełni funkcję Dyrektora podczas nieobecności Dyrektora

# Obieg dokumentu

```php
class Employee {
    public $id;
    public $name;
    public $isOnLeave = false;
    public $substitute = null;

    public function __construct($id, $name, $isOnLeave = false, $substitute = null) {
        $this->id = $id;
        $this->name = $name;
        $this->isOnLeave = $isOnLeave;
        $this->substitute = $substitute;
    }

    public function getActiveApprover() {
        return $this->isOnLeave && $this->substitute ? $this->substitute : $this;
    }
}

class DocumentWorkflow {
    private $manager;
    private $director;

    public function __construct(Employee $manager, Employee $director) {
        $this->manager = $manager;
        $this->director = $director;
    }

    public function approveByManager() {
        $approver = $this->manager->getActiveApprover();
        echo "Pismo zaakceptowane przez: {$approver->name}\n";
    }

    public function approveByDirector() {
        $approver = $this->director->getActiveApprover();
        echo "Pismo zatwierdzone przez: {$approver->name}\n";
    }

    public function processDocument() {
        $this->approveByManager();
        $this->approveByDirector();
    }
}

// Przykładowe dane
$manager = new Employee(1, 'Kierownik', true, new Employee(3, 'Zastępca Kierownika'));
$director = new Employee(2, 'Dyrektor', false);

// Procesowanie dokumentu
$workflow = new DocumentWorkflow($manager, $director);
$workflow->processDocument();

```

Dokument zostaje utworzony i jest do niego przypisany Dyrektor i Kierownik. W przypadku, gdy nie ma albo Kierownika albo Dyrektora, zostaje zaakceptowany lub zatwierdzony przez odpowiedniego Zastępcę, który został wcześniej wskazany.

# Odnotowywanie faktu zastępstwa

```sql
CREATE TABLE employees (
    id INT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    position ENUM('Autor', 'Kierownik', 'Dyrektor', 'Zastępca Kierownika', 'Zastępca Dyrektora') NOT NULL,
    is_on_leave BOOLEAN DEFAULT FALSE
);

CREATE TABLE documents (
    id INT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    created_by INT,

    assigned_manager INT,
    assigned_director INT,

    approved_by_manager BOOLEAN DEFAULT FALSE,
    manager_approval_date DATETIME,
    approved_by_director BOOLEAN DEFAULT FALSE,
    director_approval_date DATETIME,
    FOREIGN KEY (created_by) REFERENCES employees(id),
    FOREIGN KEY (assigned_manager) REFERENCES employees(id),
    FOREIGN KEY (assigned_director) REFERENCES employees(id)
);

CREATE TABLE substitutions (
    employee_id INT,
    substitute_id INT,
    start_date DATE,
    end_date DATE,
    PRIMARY KEY (employee_id, substitute_id),
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (substitute_id) REFERENCES employees(id)
);
```

Tabela `substitutions` pozwala przypisać zastępstwo na konkretny czas (`start_date`, `end_date`). Jeżeli pracownik (który jest zapisany w tabeli `employees`) ma zaznaczone `is_on_leave`, można wtedy wyszukać w tabeli `substitutions` jego zastępcę.
