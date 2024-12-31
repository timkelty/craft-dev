---

## Architecture Tests

### [Architecture](tests/Architecture/ArchitectureTest.php)

_Tests the architecture of the plugin._

![Pass](https://raw.githubusercontent.com/putyourlightson/craft-generate-test-spec/main/icons/pass.svg) Source code does not contain any `Craft::dd` statements.  
![Pass](https://raw.githubusercontent.com/putyourlightson/craft-generate-test-spec/main/icons/pass.svg) Source code does not contain any `var_dump` or `die` statements.  

## Feature Tests

### [Signals](tests/Feature/SignalsTest.php)

_Tests the signals model._

![Pass](https://raw.githubusercontent.com/putyourlightson/craft-generate-test-spec/main/icons/pass.svg) Test getting a signal value.  
![Pass](https://raw.githubusercontent.com/putyourlightson/craft-generate-test-spec/main/icons/pass.svg) Test getting a signal value using a magic call.  
![Pass](https://raw.githubusercontent.com/putyourlightson/craft-generate-test-spec/main/icons/pass.svg) Test getting a nested signal value.  
![Pass](https://raw.githubusercontent.com/putyourlightson/craft-generate-test-spec/main/icons/pass.svg) Test getting a missing signal value.  
![Pass](https://raw.githubusercontent.com/putyourlightson/craft-generate-test-spec/main/icons/pass.svg) Test getting a missing signal value using a magic call.  
![Pass](https://raw.githubusercontent.com/putyourlightson/craft-generate-test-spec/main/icons/pass.svg) Test adding a signal.  
![Pass](https://raw.githubusercontent.com/putyourlightson/craft-generate-test-spec/main/icons/pass.svg) Test adding a signal using a magic call.  
![Pass](https://raw.githubusercontent.com/putyourlightson/craft-generate-test-spec/main/icons/pass.svg) Test modifying an existing signal.  
![Pass](https://raw.githubusercontent.com/putyourlightson/craft-generate-test-spec/main/icons/pass.svg) Test modifying an existing signal using a magic call.  
![Pass](https://raw.githubusercontent.com/putyourlightson/craft-generate-test-spec/main/icons/pass.svg) Test adding a nested signal.  
![Pass](https://raw.githubusercontent.com/putyourlightson/craft-generate-test-spec/main/icons/pass.svg) Test modifying an existing nested signal.  
![Pass](https://raw.githubusercontent.com/putyourlightson/craft-generate-test-spec/main/icons/pass.svg) Test removing a signal value.  
![Pass](https://raw.githubusercontent.com/putyourlightson/craft-generate-test-spec/main/icons/pass.svg) Test removing a nested signal value.  

### [Sse](tests/Feature/SseTest.php)

_Tests the SSE service._

![Pass](https://raw.githubusercontent.com/putyourlightson/craft-generate-test-spec/main/icons/pass.svg) Test that calling an SSE method when another one is in process throws an exception.  

### [Variable](tests/Feature/VariableTest.php)

_Tests the Datastar variable._

![Pass](https://raw.githubusercontent.com/putyourlightson/craft-generate-test-spec/main/icons/pass.svg) Test that creating an action containing a reserved variable name throws an exception.  
![Pass](https://raw.githubusercontent.com/putyourlightson/craft-generate-test-spec/main/icons/pass.svg) Test that creating an action containing an object variable throws an exception.  
