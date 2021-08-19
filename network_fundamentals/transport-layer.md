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

- Port numbers are 16-bit long and range from 0 and 65,535
- The first 1023 ports are reserved for certain applications and are called well-known ports. For example, port 80 is reserved for HTTP.

The transport layer **labels** packets with the port number of the application a message is from and the one it is addressed to. This is what allows the layer to multiplex and demultiplex data.

### Ports

- **Sockets**, which are gateways to applications, are identified by a combination of an **IP address** and a 16-bit **port number**. That means `2^16 = 65536` port numbers exist. However, they start from port 0 so they exist in the range of `0 - 65536`.
- Out of these, the port numbers `0 - 1023` are **well-known** and are reserved for certain standard protocols.
- Refer to page 16 of [RFC 1700](https://tools.ietf.org/pdf/rfc1700.pdf) for more details regarding what port number is assigned to what protocol.

### Using UDP

- When a datagram is sent out from an application, the port number of the associated **source** and **destination** application is appended to it in the UDP header.
- When the datagram is received at the receiving host, it sends the datagram off to the relevant application's socket based on the **destination port number**.
- If the source port and source IP address of two datagrams are different but the destination port and IP address are the same, the datagrams will still get sent to the same application.

### On Port Assignment in UDP

It’s far more common to let the port on the client-side of an application be assigned dynamically instead of choosing a particular port. This is because for communication, both parties must be able to identify each other. Since the client initiates communication to the server, it must know the port number of the application on the server. However, the server doesn’t need to know the client application’s port number in advance. When the first datagram from the client reaches the server, it will carry the client port number, which the server can use to send datagrams back to the client.

However, server-side applications generally do not use dynamically allocated ports! This is because they are running well-known protocols like HTTP and need to be bound to specific ports.

## Congestion Control

When more packets than the network has bandwidth for are sent through, some of them start getting dropped and others get delayed. This phenomenon leads to an overall drop in performance and is called **congestion**.

Congestion physically occurs at the network layer, i.e. in routers, however, it's mainly caused by the transport layer sending too much data at once. That means it will have to be dealt with or 'controlled' at the transport layer as well.

Congestion control is really just congestion avoidance. Here's how the transport layer controls congestion:

1. It sends packets at a slower rate in response to congestion.
2. The 'slower rate' is still fast enough to make efficient use of the available capacity.
3. Changes in the traffic are also kept track of.

## Bandwidth Allocation Principles

Question: Should bandwidth be allocated to each host or to each _connection_ made by a host?

Not all hosts are created equal; Some can send and receive at a higher data rate than others. Furthermore, if the bottleneck bandwidth was allocated equally to all hosts, some of them wouldn’t be able to use the bandwidth to its full capacity and some wouldn’t have enough. For example, if an Internet-enabled doorbell and a busy server had the same bandwidth, the doorbell would have too much and the server would likely not have enough. The per-connection allocation, on the other hand, can be exploited by hosts opening multiple connections to the same end-system. Usually, bandwidth is allocated per connection.

### Efficiency and Power

- Bandwidth cannot be divided and allocation equally amonst end-systems because real traffic is transmitted in **bursts** and not in one continuous stream. Simultaneous bursts of traffic from all end-systems can cause more than the allocated bandwidth to be used which results in congestion and a consequent drop in performance.
- Congestion occurs _before_ the maximum capacity of the network is reached and **congestion collapse** occurs as it's approach. Congestion collapse occurs when all end-systems are sending a lot of traffic but nothing is being received, for example, when all or most packets are dropped. There a few causes for this, including but not limited to Spurious retransmissions.

## Max-min Fairness

Usually, bottleneck links are wide-area links that are much more expensive to upgrade than the local area networks. Mathematically, the control scheme should ensure that the sum of the transmission rate allocated to all hosts at any given time should be approximately equal to the bottleneck link’s bandwidth.

Furthermore, the congestion control scheme should be fair. Most congestion schemes aim at achieving **max-min fairness**. An allocation of transmission rates to sources is said to be **max-min fair** if:

1. No link in the network is congested
2. The rate allocated to a source _j_ cannot be increased without decreasing the rate allocated to another source _i_, whose allocation is smaller than the rate allocated to the source _j_.

In other words, this principle postulates that **increasing the transmission rate of one end-system necessarily decreases the transmission rate allocated to another end-system with an equal or smaller allocation**.

## Network Layer Imperfections

The transport layer must deal with the imperfections of the network layer service. There are three types of imperfections that must be considered by the transport layer:

1. Segments can be **corrupted** by transmission errors
2. Segments can be **lost**
3. Segments can be **reordered** or **duplicated**

### Checksum

The first imperfection of the network layer is that segments **may be corrupted by transmission errors**. The simplest error detection scheme is the **checksum**.

A checksum can be based on a number of schemes. One possible scheme is an arithmetic sum of all the bytes of a segment. Checksums are computed by the sender and attached with the segment. The receiver verifies it upon reception and can choose what to do in case it is not valid. Quite often, the segments received with an invalid checksum are **discarded**.

### Retransmission Timers

The second imperfection of the network layer is that **segments may be lost**. Since the receiver sends an acknowledgment segment after having received each data segment, the simplest solution to deal with losses is to use a **retransmission timer**.

A retransmission timer starts when the sender sends a segment. The value of this retransmission timer should be greater than the **round-trip-time**, for example, the delay between the transmission of a data segment and the reception of the corresponding acknowledgment. When the retransmission timer expires, the sender assumes that the data segment has been lost and retransmits it.

Unfortunately, retransmission timers alone are not sufficient to recover from segment losses. Let us consider the situation where an acknowledgment is lost. In this case, the sender retransmits a data segment that has been received correctly, but not properly acknowledged.

### Sequence Numbers

To identify duplicates, transport protocols associate an identification number with each segment called the **sequence number**. This sequence number is prepended to the segments and sent. This way, the end entity can identify duplicates.
