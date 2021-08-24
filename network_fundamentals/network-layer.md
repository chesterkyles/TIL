# Network Layer

## Main Objectives and Key Responsibilities

The main objective of the network layer is to allow end systems to exchange information through intermediate systems called **routers**. The unit of information in the network layer is called a **packet**.

### Limitations of the Underlying Data Link Layer

Messages at the data link layer are called **frames**. There are more than a dozen different types of data link layers.

1. Every data link layer technology has a limit on maximum frame size.
2. Most of them use a different maximum frame size.
3. Furthermore, each interface on an end system in the data link layer has a link layer address. This means the link layer has to have an addressing system of its own.

The network layer must cope with this heterogeneity of the data link layer.

### Principles of the Network Layer

The network layer relies on the following principles:

1. Each network layer entity is identified by a **network layer address**. This address is independent of the data link layer addresses that the entity may use.
2. The service provided by the network layer **does not depend** on the service or the internal organization of the **underlying data link layers**. This independence ensures:
   - **Adaptability**. The network layer can be used by hosts attached to different types of data link layers.
   - **Independent Evolution**. The data link layers and the network layer evolve independently from each other.
   - **Forward Compatibility**. The network layer can be easily adapted to new data link layers when a new type is invented.
3. The network layer is conceptually divided into **two planes**:
   1. The **data plane**. The data plane contains the protocols and mechanisms that allows _hosts and routers to exchange packets carrying user data_.
   2. The **control plane**. The control plane contains the protocols and mechanisms that _enables routers to efficiently learn how to forward packets towards their final destination_.

## Network Layer Services

There are two types of services that can be provided by the network layer:

- An unreliable connectionless service. This kind of service does not ensure message delivery and involves no established connections.
- A connection-oriented, reliable or unreliable, service. This kind of service establishes connections and may or may not ensure that messages are delivered.

Nowadays, most networks use an unreliable connectionless service at the network layer.

## Network Layer Organizations

There are two possible internal organizations of the network layer: **datagram** and **virtual circuits**.

### Datagram Organization

The datagram organization has been very popular in computer networks. Datagram-based network layers include **IPv4 and IPv6 in the global Internet**, CLNP defined by the ISO, IPX defined by Novell or XNS defined by Xerox.

This organization is **connectionless** and hence each packet contains:

- The network layer address of the destination host.
- The network layer address of the sender.
- The information to be sent.

Routers use **hop-by-hop** forwarding in the datagram organization. This means that when a router receives a packet that is not destined to itself, it looks up the destination address of the packet in its **forwarding table**.

> A **forwarding table** is a data structure that maps each destination address to the device. Then, a packet must be forwarded for it to reach its final destination.

Forwarding tables must:

- Allow any host in the network to reach any other host. This implies that **each router must know a route towards each destination**.
- The paths composed from the information stored in the forwarding tables **must not contain loops**. Otherwise, some destinations would be unreachable.

The **data plane** contains all the protocols and algorithms that are used by hosts and routers to create and process the packets that contain user data.

The **control plane** contains all the protocols and mechanisms that are used to compute, install, and maintain forwarding tables on the routers.

> **Routing tables** are generally used to generate the information for a forwarding table, which is a subset of the routing table. So, a routing table may have 3 paths for one source, and destination pair generated from a few different algorithms that’s perhaps also entered manually. The **forwarding table**, however, will only have one of those entries which is usually the preferred one based on another algorithm or criteria. The forwarding table is usually optimized for storage and lookup.

### Virtual Circuit Organization

The second organization of the network layer, called **virtual circuits**, has been _inspired by the organization of telephone networks_.

- Telephone networks have been designed to carry phone calls that usually last a few minutes.
- Each phone is **identified by a telephone number** and is attached to a **telephone switch**.
- To initiate a phone call, a telephone first needs to send the destination’s phone number to its local switch.
- The switch cooperates with the other switches in the network to create a bi-directional channel between the two telephones through the network.
- This channel will be used by the two telephones during the lifetime of the call and will be released at the end of the call.
- Until the 1960s, most of these channels were _created manually_, by telephone operators, upon request of the caller.
- Today’s telephone networks use automated switches and allow several channels to be carried _over the same physical link_, but the principles remain roughly the same.

In a network using virtual circuits, all hosts are **identified with a network layer address**. However, a host must explicitly request the establishment of a virtual circuit before being able to send packets to a destination host. The request to establish a virtual circuit is processed by the control plane, which installs state to create the virtual circuit between the source and the destination through intermediate routers.

This organization is **connection-oriented** which means that resources like buffers, CPU, and bandwidth are reserved for every connection. The first packet sent reserves these resources for subsequent packets, which all follow a single path for the duration of the connection.

The virtual circuit organization has been mainly used in public networks, starting from X.25, and then Frame Relay and Asynchronous Transfer Mode (ATM) network.

### Datagram vs Virtual Circuit Organization

#### Advantages of Datagram Organization

The main advantage of the datagram organization is that **hosts can easily send packets to any number of destinations**, while the virtual circuit organization requires the establishment of a virtual circuit before the transmission of a data packet. This can cause high overhead for hosts that exchange small amounts of data.

Another advantage of the datagram-based network layer is that **it’s resilient**. If a virtual or physical circuit breaks, it has to go through the connection establishment phase, again. In case of datagram-based network layer, **each packet can be routed independently of each other**, hop-by-hop, so intermediate routers can divert around failures.

#### Advantages of The Virtual Circuit Organization

On the other hand, the main advantage of the virtual circuit organization is that the **forwarding algorithm used by routers is simpler** than when using the datagram organization. Furthermore, the utilization of virtual circuits may allow the **load to be better spread through the network**.

Also, since the packets follow a particular dedicated path, they **reach the destination in the order they were sent**. Virtual circuits can be configured to provide a variety of services including best effort, in which case some packets may be dropped. However, in case of bursty traffic, there is a possibility of packet drops.

## Control Plane: Static and Dynamic Routing

The main purpose of the **control plane** is to maintain and build routing tables. This is done via a number of algorithms and protocols which we will discuss here.

### Static Routing

Manually computed routes are manually added to the routing table. This is useful if there are a few outgoing links from your network. It gets difficult when you have rich connectivity (in terms of the number of links to other networks). It also does not automatically adapt to changes – addition or removal of links or route. The disadvantages of this are:

1. The main drawback of static routing is that it doesn’t adapt to the evolution of the network and hence doesn’t scale well. When a new route or link is added, all routing tables must be recomputed.
2. Furthermore, when a link or router fails, the routing tables must be updated as well.

### Dynamic Routing

Unlike static routing algorithms, dynamic ones adapt routing tables with changes in the network. There are two main classes of dynamic routing algorithms: **distance vector** and **link-state routing algorithms**.

**Distance vector** is a simple distributed routing protocol. Distance vector routing allows routers to discover the destinations reachable inside the network as well as the shortest path to reach each of these destinations. The shortest path is computed based on the cost that is associated with each link.

Another way to create a routing table with the most efficient path between two routers or ‘nodes’ is by using **link-state routing**. Link-state routing works in two phases: **reliable flooding** and **route calculation**.

Routers running distance vector algorithms share summarized reachability information with their neighbors. Every router running link-state algorithms, on the other hand, builds a complete picture of the whole network (which is **phase I**) before computing the shortest path to all destinations. Then, based on this learned topology, each router is able to compute its routing table by using the shortest path computation such as _Dijkstra’s Algorithm_. This is **phase II**.

## Internet Protocol (IP)

The Internet Protocol (IP) is the network layer protocol of the TCP/IP protocol suite. The flexibility of IP and its ability to use various types of underlying data link layer technologies is one of its key advantages. The current version of IP is version 4 and is specified in [RFC 791](http://tools.ietf.org/html/rfc791.html).

## IP version 4 (IPv4)

The design of IPv4 was based on the following assumptions:

- IP should provide an _unreliable connectionless service_
- IP operates with the _datagram transmission mode_
- IP hosts must have _fixed size 32-bit addresses_
- IP must be _compatible with a variety of data link layers_
- IP hosts should be able to _exchange variable-length packets_

### Multihoming

An IPv4 address is used to identify an interface on a router or an interface on a host. A router has thus as many IPv4 addresses as the number of interfaces that it has in the data link layer. Most hosts have a single data link layer interface and thus have a single IPv4 address. However, with the growth of wireless more and more hosts have several data link layer interfaces (for example, an Ethernet interface and a WiFi interface). These hosts are said to be **multihomed**. A multihomed host with two interfaces has thus two IPv4 addresses.

### Address Assignment

Appropriate network layer address allocation is key to the efficiency and scalability of the Internet. A naive allocation scheme would be to provide an IPv4 address to each host when the host is attached to the Internet on a first come, first served basis.  Unfortunately, this would force all routers to maintain a specific route towards all approximately 1 Billion hosts on the Internet, which is not scalable. Hence, it’s important to minimize the number of routes that are stored on each router.

#### Subnetting

One solution is that routers should only maintain routes towards **blocks of addresses** and not towards individual hosts. For this, blocks of IP addresses are assigned to ISPs. The ISPs assign sub blocks of the assigned address space in a hierarchical manner. **These sub blocks of IP addresses are called subnets**. An IPv4 address is composed of two parts:

- A **subnetwork identifier** composed of the high order bits of the address.
- And a **host identifier** encoded in the lower order bits of the address.

#### Address Class

RFC 791 proposed to use the high-order bits of the address to encode the length of the subnet identifier. This led to the definition of three classes of addresses.

 Class | High-order bits | Length of subnet id | Number of networks | Address per network
 ----- | --------------- | ------------------- | ------------------ | -------------------
 Class A | `0` | `8` bits | `128 (2^7)` | `16,2777,216 (2^24)`
 Class B | `10` | `16` bits | `16,384 (2^14)` | `16,2777,216 (2^16)`
 Class C | `110` | `24` bits | `2,097,1521 (2^21)` | `256 (2^8)`

In this **classful address scheme**, the range range of the IP addresses in each class are as follows:

- **Class A**: `0.0.0.0` to `127.255.255.255`
- **Class B**: `128.0.0.0` to `191.255.255.255`
- **Class C**: `192.0.0.0` to `223.255.255.255`
- **Class D**: `224.0.0.0` to `239.255.255.255`
- **Class E**: `240.0.0.0` to `255.255.255.255`

**Class D** IP addresses are used for multicast, whereas **class E** IP addresses are reserved and can’t be used on the Internet. So classes A, B, and C are the ones used for regular purposes.

Read more about IPv4 especially subnet masks, default subnet masks, variable-length subnets, network address and broadcast address.

