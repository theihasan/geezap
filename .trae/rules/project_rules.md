# Geezap Project Rules
## Framework & Language Standards
- Follow Laravel 12 coding standards and conventions
- Utilize PHP 8.3 features and syntax
- Enable strict type checking with declare(strict_types=1); at the top of every PHP file

## Code Quality Requirements
- Write efficient code following Laravel best practices
- Optimize for performance with time complexity considerations
- Use proper naming conventions following PSR standards
- Implement comprehensive test cases for all features
- Use phpunit latest for testing.

## Error Handling
- Use proper exception handling with try-catch blocks
- Create and utilize custom exception classes for specific error scenarios

## HTTP Requests
- Use Laravel macros for HTTP calls to ensure consistency

## Testing
- Write comprehensive test cases for all features
- Use phpunit latest for testing.
- When mocking, do not use Mockery::mock('alias:', ...), instead mock the class, and use $this->app->instance(...) to register the instance. 
- While writing tests, consider upto 10 edge cases.
- Reuse code by creating reusable functions between test cases. 
- Use phpunit latest version with #Test[] attributes for test cases.