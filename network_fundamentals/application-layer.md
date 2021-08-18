# Application Layer

## Key Responsibilities

The main job of the application layer is to enable end-users to access the Internet via a number of applications. This involves:

- Writing data off to the network in a format that is compliant with the protocol in use.
- Reading data from the end-user.
- Providing useful applications to end users.
- Some applications also ensure that the data from the end-user is in the correct format.
- Error handling and recovery is also done by some applications.

## Network Application Architectures

### Client-Server Architecture

In this architecture, a network application consists of two parts: **client-side** software and **server-side** software. These pieces of software are generally called **processes** and they communicate with each other through **messages**.

#### Servers

The server process controls access to a centralized resource or service such as a website. Servers have two important charactersistics:

1. Generally, an attempt is made to keep server online all the time, although 100% availability is impossible. Furthermore, servers set up as a hobby or as an experiment may not need to be kept online. Nevertheless, the client must be able to find the server online when needed, otherwise, communication wouldn't take place.
2. They have at least one reliable IP address with which they can be reached.

#### Clients

Client processes use the Internet to consume content and use the services. Client processes almost always initiate connection to server, while server processes wait for requests from clients.

### Data Centers

When client-server applications scale, one or even two servers can't handle the requests from a large number of clients. Additionally, servers may crash due to any reason and might stop working. Most applications have serveral servers in case one fails. Therefore, several machines host server processes (called servers too), and they reside in **data center**.

### Peer-to-Peer Architecture (P2P)

In this architecture, applications on end-systems called 'peers' communicate with each other. No dedicated server or large data is involved. Peers mostly reside on PCs like laptops and desktops in homes, offices, and universitites. The key advantage is that it can scale rapidly - without the need of spending large amounts of money, time or effort.

Regardless of P2P's decentralized nature, each peer can be categorized as servers or clients i.e., every machine is capable of being a client as well as a server.

## Program vs Process vs Thread

- A **program** is simply an executable file. An application such as MS Word is one example.
- A **process** is any currently running instance of a program. So one program can have several copies of it running at once. One MS Word program can have multiple open windows.
- A **thread** is a lightweight process. One process can have multiple running threads. The difference between threads and processes is that threads do lightweight singular jobs.

## Sockets

Processes on different machines send messages to each other through the computer network. The _interface_ between a process and the computer network is called a **socket**. Note that sockets do not have anything to do with hardware - they are software interfaces.

## Addressing

Messages have to be addressed to a certain application on a certain end system. It is done via addressing constructs like **IP addresses and ports**.

Since every end-system may have a number of applications running, **ports** are used to address the packet to specific applications. Some ports are reserved such as port 80 for HTTP and port 443 for HTTPS.

[Ephemeral Ports](https://en.wikipedia.org/wiki/Ephemeral_port): Different port numbers are dynamically generated for each instance of an application. The port is freed once the application is done using it.

Furthermore, server processes need to have well defined and fixed port numbers so that clients can connect to them in a systematic and predictable way. However, clients don't need to have reserved ports. They can use ephemeral ports.

## HTTP (HyperText Transfer Protocol)

- Web pages are objects that consists of other objects.
- An object is simply a file like an HTML file, PNG file, MP3 file, etc.
- Each object has a URL.
- The base object of a web page is often an HTML file that has references to other objects by making requests for them via their URL.

A **URL** is used to locate files that exists on servers. URLs consist of the following parts:

- Protocol in use
- The hostname of the server
- The location of the file
- Arguments to the file

HTTP is a client-server protocol hat specifies how Web clients request Web pages from Web servers and how Web servers send them.

```txt
CLIENT  --> HTTP Request ----> SERVER
CLIENT  <-- HTTP Response <--- SERVER
```

There is a whole class of protocols that are considered **request-response protocols**. HTTP is one of them. Note that HTTP is a **stateless protocol**: servers do not store any information about clients by default. So if a client requests the same object multiple times in a row, the server would send it and would not know that the same client is requesting the same object repeatedly.

### HTTP Requires Lower Layer Reliability

- Application layer protocols rely on underlying transport layer protocols called **UDP** (User Datagram Protocol) and **TCP** (Transmission Control Protocol).
- **TCP ensures that messages are always delivered**. Messages get delivered in the order that they are sent.
- **UDP does not ensure that messages get delivered**. This means that some messages may get dropped and so never be received.
- **HTTP uses TCP** as its underlying transport protocol so that messages are guaranteed to get delivered in order. This allows the application to function without having to build any extra reliability as it would've had to with UDP.
- **TCP is connection-oriented**, meaning a connection has to be initiated with servers using a series of starting messages.
- Once the connection has been made, the client exchagnes messages with the server until the connection is officially closed by sending a few ending messages.

There are two types of HTTP Connections:

- **Non-persistent HTTP connections**
- **Persistent HTTP connections**

#### Non-persistent HTTP Connections

These use **one TCP connection per request**. Assume a client requests the base HTML file of a web page. The following are what happens:

- The client initiates a TCP connection with a server
- The clien
