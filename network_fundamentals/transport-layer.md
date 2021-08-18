# Transport Layer

## Key Responsibilities

- **Extends network to the applications** : the transport layer takes messages from the network to applications. In other words, while the network layer (directly below this layer) transports messages from one end-system to another, the transport layer delivers the message to and from the relevant application _on_ an end-system.
- **Logical application-to-application delivery** : the transport layer makes it so that applications can address other applications on other end-systems directly. This is true even if it exists halfway across the world. So it provides a layer of **abstraction**.
- **Segments data** : The transport later also divides the data into manageable pieces called 'segments' or 'datagrams'.
- **Can allow multiple conversations** : Tracks each application to application connection or 'conversation' separately, which can allow multiple conversations to occur at once.
- **Multiplexes and demultiplexes data** : Ensures that the data reaches the relevant application _within_ an end-system. So if multiple packets get sent to one host, each will end up at the correct application.

## Transport Layer Protocols

The transport layer has two prominent protocols: the **transmission control protocol** and the **user datagram protocol**.

TCP | UDP
----| ---
Delivers messages that we call ‘segments’ reliably and in order. | Does not ensure in-order delivery of messages that we call ‘datagrams.’
Detects any modifications that may have been introduced in the packets during delivery and corrects them. | Detects any modifications that may have been introduced in the packets during delivery but does not correct them by default.
Handles the volumes of traffic at one time within the network core by sending only an appropriate amount of data at one time. | Does not ensure reliable delivery. Generally faster than TCP because of the reduced overhead of ensuring uncorrupted delivery of packets in order.
Examples of applications/application protocols that use TCP are: **HTTP**, **E-mail**, **File Transfers**. | Applications that use UDP include: **Domain Name System (DNS)**, **live video streaming**, and **Voice over IP (VoIP)**.

## Multiplexing and Demultiplexing

End-systems typically run a variety of applications at the same time. For example, at any given time a browser, a music streaming service, and an email agent could be running. So how does the end-system know which process to deliver packets to? Well, that's where the transport layer's demultiplexing comes in.

**Demultiplexing** is the process of delivering the correct packets to the correct applications from one stream.

**Multiplexing** allows messages to be sent to more than one destination host via a single medium.

Multiplexing and demultiplexing are usually a concern when one protocol (TCP for example) is used by many others (HTTP, SMTP, FTP) in an upper layer.

### How Do They Work?

Recall that **sockets** are gateways between applications and the network, i.e., if an application wants to send something over to the network, it will write the message to its socket. Sockets have an associated **port number** with them.

- Port numbers are 16-bit long and range from 0 and 65,535/
- The first 1023 ports are reserved for certain applications and are called well-known ports. For example, port 80 is reserved for HTTP.

The transport layer **labels** packets with the port number of the application a message is from and the one it is addressed to. This is what allows the layer to multiplex and demultiplex data.

### Ports

- **Sockets**, which are gateways to applications, are identified by a combination of an **IP address** and a 16-bit **port number**. That means $2^{16} = 65536$ port numbers exist. However, they start from port $0$ so they exist in the range of $0-65536$.

### Using UDP

