# Data Link Layer

## Responsibilities of the Data Link Layer

The data link layer receives packets from the network layer and deals with **providing hop to hop communication** or communication between entities that are **directly connected by a physical link**.

In other words, it makes intelligible communication possible over a physical link that just transports 0s and 1s between two directly connected hosts.

## Types of Data Link Layers

The data link layer is the lowest layer of the reference model that we will discuss in detail. Data link layer protocols exchange **frames** that are transmitted through the physical layer. There are three main types of data link layers:

1. The _simplest_ data link layer type is one that has only **two communicating systems connected directly through the physical layer** also known as the **point-to-point data link layer**. This type of layer can either provide an unreliable service or a reliable service. The unreliable service is frequently used above physical layers (e.g., optical fiber, twisted pairs) that have a low bit error ratio, while reliability mechanisms are often used in wireless networks to recover locally from transmission errors.
2. The second type of data link layer is the one used in Local Area Networks (LAN) called **Broadcast multi-access**. Both end-systems and routers can be connected to a LAN.
   - An important difference between point-to-point data and Broadcast multi-access is that in a Broadcast multi-access, **each communicating device is identified by a unique data link layer address**. This address is usually embedded in the hardware of the device and different types of LANs use different types of data link layer addresses. However, since there is only one party at the “other end of the wire,” in point-to-point, there is no ambiguity what entity should receive a frame that is transmitted, thus there is no need for addressing.
   - A communicating device attached to a LAN can send a data link frame to any other communication device that is attached to the same LAN.
   - Most LANs also **support special broadcast and multicast data link layer addresses**. A frame sent to the broadcast address of the LAN is delivered to all communicating devices that are attached to the LAN. The multicast addresses are used to send a frame to one specific group.
3. The third type of data link layer is used in **Non-Broadcast Multi-Access (NBMA) networks**. These networks are used to interconnect devices like a LAN. All devices attached to an NBMA network are identified by a unique data link layer address.
   - The main difference between an NBMA network and a traditional LAN is that the NBMA service **only supports unicast and supports neither broadcast nor multicast**.
   - ATM, Frame Relay and X.25 are examples of NBMA.

## Limitations Imposed Upon The Data Link Layer

### By the Data Link Layer

The data link layer uses the service provided by the physical layer. Although there are many different implementations of the physical layer from a technological perspective, they all provide a service that enables the data link layer to send and receive bits between directly connected devices. Most data link layer technologies **impose limitations on the size of the frames**:

1. Some technologies only impose a maximum frame size.
2. Others enforce both minimum and maximum frame sizes.
3. Finally, some technologies only support a single frame size. In this case, the data link layer will usually include an adaptation sub-layer to allow the network layer to send and receive variable-length packets. This adaptation layer may include fragmentation and reassembly mechanisms.

### By the Physical Layer

The physical layer service facilitates the sending and receiving of bits, but it’s usually far from perfect:

- The physical layer **may change the value of a bit** being transmitted due to any reason, e.g., electromagnetic interferences.
- The Physical layer **may deliver more bits** to the receiver than the bits sent by the sender.
- The Physical layer **may deliver fewer bits** to the receiver than the bits sent by the sender.

## The Framing Problem

The data link layer must allow end systems to exchange frames containing packets despite all of these limitations.

On point-to-point links and Local Area Networks, the first problem to be solved is **how to encode a frame as a sequence of bits** so that the receiver can easily recover the received frame **despite the limitations of the physical layer**. This is the **framing problem**. It can be defined as: "_How does a sender encode frames so that the receiver can efficiently extract them from the stream of bits that it receives from the physical layer?_”

The following are some of the solutions:

1. **Idle Physical Layer** : A first solution to solve the framing problem is to **require the physical layer to remain idle for some time after the transmission of each frame**. These idle periods can be detected by the receiver and serve as a marker to indicate frame boundaries.
2. **Multi-symbol Encodings** : **All physical layer types are able to send and receive physical symbols that represent values 0 and 1**. Also, **several physical layer types are able to exchange other physical symbols as well**. Some technologies use these other special symbols as markers for the beginning or end of frames. For example, the Manchester encoding used in several physical layers can send four different symbols.
3. **Stuffing** : Unfortunately, multi-symbol encodings cannot be used by all physical layer implementations and a generic solution with which any physical layer that is able to transmit and receive only 0s and 1s works is required. This **generic solution is called stuffing** and two variants exist: bit stuffing, and character stuffing. To enable a receiver to easily delineate the frame boundaries, these two techniques **reserve special bit strings** as frame boundary markers and encode the frames such that these special bit strings do not appear inside the frames.

### Bit Stuffing

Bit stuffing is the insertion of non information bits into data. Bit stuffing reserves a special bit pattern, for example, the `01111110` bit string as the frame boundary marker. However, if the same bit pattern occurs in the data link layer payload, it must be modified before being sent, otherwise, the receiving data link layer entity will detect it as a start or end of frame. For example:

Original Frame | Transmitted Frame
-------------- | -----------------
0001001001001001001000011 | 01111110000100100100100100100001101111110
01111110 | 0111111001111101001111110

For example, consider the transmission of 0110111111111111111110010.

1. The sender will first send the 01111110 marker followed by 011011111.
2. After these five consecutive bits set to 11, it inserts a bit set to 00 followed by 11111.
3. A new 0 is inserted, followed by 11111.
4. A new 0 is inserted followed by the end of the frame 110010 and the 01111110 marker.

Read more about bit stuffing here: <https://www.tutorialspoint.com/what-is-bit-stuffing-in-computer-networks>

### Character Stuffing

This technique operates on frames that contain an integer number of characters of a fixed size, such as 8-bit characters. Some characters are used as markers to delineate the frame boundaries. Many character stuffing techniques use the DLE, STX and ETX characters of the ASCII character set. DLE STX is used to mark the beginning of a frame, and DLE ETX is used to mark the end of a frame.

> Software implementations prefer to process characters than bits, hence software-based data link layers usually use character stuffing.

For example, to transmit frame 1 2 3 DLE STX 4:

1. A sender will first send DLE STX as a marker
2. Followed by 1 2 3 DLE
3. Then, the sender transmits an additional DLE character
4. Followed by STX 4 and the DLE ETX marker
5. The final string is: DLE STX 1 2 3 DLE DLE STX 4 DLE ETX

Original Frame | Transmitted Frame
-------------- | -----------------
1 2 3 4 | DLE STX 1 2 3 4 DLE ETX
1 2 3 DLE STX 4 | DLE STX 1 2 3 DLE DLE STX 4 DLE ETX
DLE STX DLE ETX | DLE STX DLE DLE STX DLE DLE ETX DLE ETX

> DLE is the bit pattern 00010000, STX is 00000010 and ETX is 00000011.

Read more about character stuffing here: <https://www.tutorialspoint.com/what-is-byte-stuffing-in-computer-networks>

Disadvantages of Stuffing:

1. In character stuffing and in bit stuffing, the length of the transmitted frames is increased. The worst case redundant frame in case of bit stuffing is one that has a long sequence of all 1s, whereas in the case of character stuffing, it’s a frame consisting entirely of DLE characters.
2. When transmission errors occur, the receiver may incorrectly decode one or two frames (e.g., if the errors occur in the markers). However, it’ll be able to resynchronize itself with the next correctly received markers.
3. Bit stuffing can be easily implemented in hardware. However, implementing it in software is difficult given the higher overhead of bit manipulations in software.

## Error Detection
