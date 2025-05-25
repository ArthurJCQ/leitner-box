# Project Guidelines
    
This project is using PHP 8.4 latest features, and Symfony framework. A clean Architecture is used to organize folders.
No references to the frameworks or any external libraries should occur from business logic, interfaces exist as port for any external adapters.

Project structure:
- Application: Everything related to the business rules and use cases.
- Domain: Here lies the Model (business objects) and the interfaces
- Infrastructure: All the code related to the framework (here, Symfony) and external librairies.

Good practices:
- Latest PHP 8.4 features should be used when applicable
- Immutability for Business model objects
- Business objects should have a behavior
- Strict coding style from PSR2: https://www.php-fig.org/psr/psr-2/ & PSR12: https://www.php-fig.org/psr/psr-12/
- One blank line around control structures (if, foreach, etc...), except if it's the first or the last instruction of the current function
- One blank line before any return or yield statement, except if it is the only statement of the function.
- Use generics typing when applicable, and type-hint arrays through PHP annotations (for params and returned array).
- Never use the empty() function, see https://localheinz.com/articles/2023/05/10/avoiding-empty-in-php/
- Always put single line comments if there is a unique PHP annotation. For instance: /** @param Article $article */
- If PHP annotation require multiple annotations (several @params, a @return, ...) put it on multi-line
