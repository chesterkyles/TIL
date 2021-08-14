# Dockerfile

Docker can build images automitaclly by reading the instructions from a `Dockerfile`. It is a text document taht contains all the commands a user could call on the command line to assemble an image.

## Format

Here is the format of the `Dockerfile`:

```dockerfile
# Comment
INSTRUCTION arguments
```

Note that the instruction is not case-senstitive; however, the convention is to use UPPERCASE to distinguish them from arguments more easily.

Docker runs instructions in a `Dockerfile` in order. A `Dockerfile` **must begin with a `FROM` instruction**. The `FROM` instruction specified the _Parent Image_ from which you are building which may be preceded by one or more `ARG` instructions.

Docker treats lines that _begin_ with a `#` as a comment, unless the line is a valid parser directive. A `#` marker anywhere else in a line is treated as an argument.

Read more about `format` here: <https://docs.docker.com/engine/reference/builder/#format>

## Parser Directives

Parser directives are optional. They do not addl layers to the build, and will not be shown as a build step. They are written as special type of comment in the form `# directive=value`.

Once a comment, empty line or builder instruction has been processed, Docker no longer looks for parser directives. Instead, it treats anything formatted as a parser directive as a comment and does not attempt to validate if it might be a parser directive. Therefore, all parser directives must be at the very top of a `Dockerfile`.

Read more about Parser Directives here: <https://docs.docker.com/engine/reference/builder/#parser-directives>

The following parser directives are supported:

- `syntax`
- `escape`

### Syntax

```dockerfile
# syntax=[remote image reference]
```

The syntax directive defines the location of the Dockerfile syntax that is used to build the Dockerfile. The BuildKit backend allows to seamlessly use external implementations that are distributed as Docker images and execute inside a container sandbox environment.

### Escape

```dockerfile
# escape=\ (backslash)

# OR

# escape=` (backtick)
```

The `escape` directive sets the character used to escape characters in a `Dockerfile`. If not specified, the default escape character is `\`. It is used both to escape characters in a line, and to escape a newline. This allows a `Dockerfile` instruction to span multiple lines.

Consider the following example which would fail in a non-obvious way on `Windows`. The second `\` at the end of the second line would be interpreted as an escape for the newline, instead of a target of the escape from the first `\`. Similarly, the `\` at the end of the third line would, assuming it was actually handled as an instruction, cause it be treated as a line continuation. The result of this dockerfile is that second and third lines are considered a single instruction:

```dockerfile
FROM microsoft/nanoserver
COPY testfile.txt c:\\
RUN dir c:\
```

Results in:

```txt
PS E:\myproject> docker build -t cmd .

Sending build context to Docker daemon 3.072 kB
Step 1/2 : FROM microsoft/nanoserver
  ---> 22738ff49c6d
Step 2/2 : COPY testfile.txt c:\RUN dir c:
GetFileAttributesEx c:RUN: The system cannot find the file specified.
PS E:\myproject>
```

## Environment Replacement

Environment variables (declared with the `ENV` statement) can also be used in certain instructions as variables to be interpreted by the `Dockerfile`.

Environment variables are notated in the `Dockerfile` either with `$variable_name` or `${variable_name}`. They are treated equivalently and the brace syntax is typically used to address issues with variable names with no whitespace, like `${foo}_bar`.

The `${variable_name}` syntax also supports a few of the standard bash modifiers as specified below:

- `${variable:-word}` indicates that if `variable` is set then the result will be that value. If `variable` is not set then `word` will be the result.
- `${variable:+word}` indicates that if `variable` is set then `word` will be the result, otherwise the result is the empty string.

In all cases, `word` can be any string, including additional environment variables.

Escaping is possible by adding a `\` before the variable: `\$foo` or `\${foo}`, for example, will translate to `$foo` and `${foo}` literals respectively.

```dockerfile
FROM busybox
ENV FOO=/bar
WORKDIR ${FOO}   # WORKDIR /bar
ADD . $FOO       # ADD . /bar
COPY \$FOO /quux # COPY $FOO /quux
```

## .dockerignore file

Before the docker CLI sends the context to the docker daemon, it looks for a file named `.dockerignore` in the root directory of the context. If this file exists, the CLI modifies the context to exclude files and directories that match patterns in it. This helps to avoid unnecessarily sending large or sensitive files and directories to the daemon and potentially adding them to images using `ADD` or `COPY`.

Here is an example:

```dockerignore
# comment
*/temp*
*/*/temp*
temp?
```

Docker also supports a special wildcard string `**` that matches any number of directories (including zero). Lines starting with `!` (exclamation mark) can be used to make exceptions to exclusions.

Detailed examples are shared on this link: <https://docs.docker.com/engine/reference/builder/#dockerignore-file>

## FROM

```Dockerfile
FROM [--platform=<platform>] <image> [AS <name>]
# or
FROM [--platform=<platform>] <image>[:<tag>] [AS <name>]
# or
FROM [--platform=<platform>] <image>[@<digest>] [AS <name>]
```

