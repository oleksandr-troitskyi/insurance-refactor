## Code review

Please review the sample code, as if it was submitted as a PR by a junior team member.
You are the owner of the component this functionality belongs to and are responsible for the results.

- Would it be safe to go into production?
- Does it follow best practices?
- How could we verify that it's always working as intended?

### Coding review resolution
**It took me around 60-70 minutes to analyze code and write this review.**

This PR is not safe to go into production, as here are no unit or functional tests provided, and there are some mistakes 
where made that breaks program in some cases.  
 
Let's go to problems this PR bring us, if it will be delivered as is:  
- I'd like to see some tests to cover this class functionality. Some functional tests at least, as this code provided 
allows us only this kind of tests. Looking ahead, those tests will show, that method fails on some cases.
- Code will fail when `quote` method takes `$providers` as a type of array. There is no validation for that case inside. 
- Other than that, name of the input parameter just forces a developer to use array, when he calls this method. So there 
is a misleading name of the input parameter. We should avoid of doing like this, as it might cause a wrong usage of our 
programs. Names of methods and input parameters should clearly display their purpose without side effects.
- Going deeper, if we send a string as input parameter `$providers`, internal code will create array in array, and this 
structure is not supposed to be used in code below.
- The issue above slightly bring us to the point, that we need a validation of input parameter. Other developers might
set `$providers` as null, array or even an object. At least, we need to set the type of the `$providers`, like this: 
```
public function quote(array $providers = null)
``` 
Then, this interface does not show to developer, will he get something back from the method, or not. Interface of the
method should show as much as it can, without the need to go and read the code. By simply adding a return type, we avoid
lots of questions from other developers, who are supposed to use this class:
```
public function quote(array $providers = null): array
``` 
- Another issue is that for HTTP requests two approaches where used - `file_get_contents()` and `cURL`. 
`file_get_contents()` is a native function, but has lots of disadvantages - slow, supports only GET and POST methods, 
and only HTTP and HTTPS requests. First disadvantage is most weighty in current circumstances. Another point is - it is 
better from the side of code support, readability and practical thoughts to use only one of them. So we need to change
code to use only one library - cURL.
- Although, there is no validation of responses from those endpoints we use. So this code will be highly dependent of 
endpoints stability. We need to use try-catch at least.
- `CURLOPT_URL` parameter uses both single and double quotes at the same time. Despite the fact that cURL library is
 smart enough and will clear this line, it's not safe for practical thoughts (developer might copy this line with double
  quotes so that this might cause unexpected errors in other parts of code).
- Talking about the cURL library, it gives us and opportunity to set connection timeout (`CURLOPT_CONNECTTIMEOUT` 
option) and cURL function execution time limit (`CURLOPT_TIMEOUT` option). We definitely don't want our application to 
stuck with those requests to the third party providers.
- Such things as URLs, `3` for `month` should be moved at least to constants or protected class parameters. This 
increases code readability and flexibility a lot.
- S, O and D from SOLID and Separation of Concerns principles are broken. One method is used for input variable 
validation (should be), choosing endpoint, making request, and response transformation. We definitely need to split this
class to several classes with one area of responsibility for each of them.
- Providing an RepositoryInterface for Repository (Dependency Inversion Principle) and Factory pattern to build 
implementations will allow us to isolate this switch-case structure.
- Next, we can get rid of switch-case structure in new Factory mentioned above. PHP ability to use variable as class 
reference allow us to use aliases of providers as parts of names of Repository classes with implementation of 
RepositoryInterface. So that we will follow then the Open-Closed Principle. With a new provider, we can simply create 
a new Repository class.
- We should hide library for HTTP requests under Facade. This gives us and opportunity to do not use all this complexity 
of third-party library with no need in it.
- We need to create a Validator class, that will be responsible only for to say us, can we work forward with this input 
 parameter `$providers` or not.
- List of allowed provides should be moved to a separate class - let's name it AllowedProvides. We are going to use this 
list twice - while setting a default value and in Validator. So this will reduce code duplication, and increase code 
readability.
- It will be awesome to use Dependency Injection pattern and set Factory, Validator and AllowedProvides as input 
parameters for `__construct()` method of Insurance class. So then we are free to replace this classes with different 
implementations, create Interfaces for them. And a huge profit - it opens us an extended ability of independent testing 
of the code in Insurance class by mocking those classes.
- After all changes beeing done, we need to write Unit tests and cover all new created classes, so that we will be 
sure - all works fine. Initial Insurance class can be covered with Unit, Integration and Functional tests.
  
  
What I would insist on after this review, is to completely refactor this code, using OOP abilities that PHP provides us, 
using all comments above.  
If I have an ability and free time, I personally think it would be better for future perspective of this junior growing 
to do a pair coding session with him. So that I could give some useful thoughts, show on practice some mistakes and 
ways to solve them.  
In other case, I can create a UML diagram or at least some draft code with a solution and quickly go through it with my 
colleague, describe main mistakes he did and ways to solve them, so that he will be able to draw conclusions for himself 
and refactor this code himself.

## Refactoring

Refactor the code to meet best practices and modern coding standards.


### Notes
**It took me 2 hours to refactor code (write from scratch will be a more correct definition) and cover it with tests.** 
**You need PHP 7.4 to run this. Although, run `composer install` inside project folder if you want to run and modify 
`index.php` or run `tests`.**
- I assumed, that in and out params (and types) are fixed, and didn't change them.
- As those endpoints for bank and insurance-company doesn't work. As you mentioned in the letter back, some developers 
"create working endpoints to mock them with some valid response data". I added some endpoints to test as well.
- Covering dependency of external service, I put all this cURL library into try-catch construction. So that we will be 
able to catch all exceptions, add improve logging of errors (I added some simple logging), and validate response. Making 
custom working endpoints forces us to do too many assumptions. On the other hand, code provided to refactor, makes me 
think that we need to return response in any way, even with empty response from endpoints.
- This solution is a light one. There might be another solution, with Domain, DTOs, some more interfaces and so on 
(close to DDD), if we assume, that in and out params can be changed. But in this case code becomes more complicated. For
 example, there might be ProviderCollection of Provider Entities, and each of this Entities may contain parameters such 
 as Provider alias, Provider name, Provider link, Provider post parameters if any. So that Factory will change a little.
 Although there is the place to bring Price Entity and Price Collection to the scene, and they will be realated to 
 Provider Entity, as Root Entity.